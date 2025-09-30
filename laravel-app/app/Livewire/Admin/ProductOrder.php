<?php

namespace App\Livewire\Admin;

use App\Events\OrderPlaced;
use App\Jobs\SendOrderConfirmationEmail;
use App\Jobs\SendOrderWebhook;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class ProductOrder extends Component
{
    use LivewireAlert, WithPagination;

    public string $search = '';

    public int $perPage = 10;

    public array $cart = []; // ['product_id' => ['quantity' => x, 'unit_price' => y]]

    public function updatingSearch()
    {
        $this->resetPage();
        $this->saveOrderLog(null, 'search_updated', 'Search term updated: '.$this->search);
    }

    // Add product to cart
    public function addToCart($productId)
    {
        $product = Product::findOrFail($productId);

        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity']++;
        } else {
            $this->cart[$productId] = [
                'quantity' => 1,
                'unit_price' => $product->price,
                'name' => $product->name,
            ];
        }

        $this->saveOrderLog(null, 'cart_add', "Product {$product->name} added to cart (ID: {$productId})");

        $this->alert('success', "{$product->name} added to cart");
    }

    // Remove product from cart
    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
        $this->saveOrderLog(null, 'cart_remove', "Product removed from cart (ID: {$productId})");
    }

    // Update quantity
    public function updateQuantity($productId, $quantity)
    {
        if ($quantity <= 0) {
            $this->removeFromCart($productId);
        } else {
            $this->cart[$productId]['quantity'] = $quantity;
            $this->saveOrderLog(null, 'cart_update', "Cart quantity updated for product ID {$productId} to {$quantity}");
        }
    }

    // Place order
    public function placeOrder()
    {
        $userId = auth()->id();
        $this->saveOrderLog(null, 'order_attempt', 'Attempting to place order', $userId);

        DB::beginTransaction();
        try {
            $order = Order::create([
                'status' => 'placed',
                'user_id' => $userId,
            ]);
            $this->saveOrderLog($order->id, 'order_created', 'Order record created', $userId);

            foreach ($this->cart as $productId => $line) {
                $product = Product::lockForUpdate()->findOrFail($productId);

                if ($product->stock_on_hand < $line['quantity']) {
                    $this->saveOrderLog($order->id, 'stock_insufficient', "Insufficient stock for {$product->name}", $userId);
                    throw new \Exception("Insufficient stock for {$product->name}");
                }

                $product->decrement('stock_on_hand', $line['quantity']);
                $this->saveOrderLog($order->id, 'stock_decremented', "Stock decremented for {$product->name}, new stock: {$product->stock_on_hand}", $userId);

                $order->lines()->create([
                    'product_id' => $product->id,
                    'quantity' => $line['quantity'],
                    'unit_price' => $line['unit_price'],
                ]);
                $this->saveOrderLog($order->id, 'order_line_created', "Order line created for product ID {$productId}", $userId);
            }

            DB::commit();
            $this->saveOrderLog($order->id, 'order_placed', 'Order placed successfully', $userId);

            // Clear cart
            $this->cart = [];

            // Dispatch confirmation email
            SendOrderConfirmationEmail::dispatch($order);
            $this->saveOrderLog($order->id, 'email_dispatched', 'Order confirmation email dispatched', $userId);

            // Emit domain event
            event(new OrderPlaced($order));
            $this->saveOrderLog($order->id, 'event_emitted', 'OrderPlaced event emitted', $userId);

            // Trigger outbound webhook
            $webhookUrl = config('services.order_webhook.url');
            $webhookSecret = config('services.order_webhook.secret');
            if ($webhookUrl && $webhookSecret) {
                SendOrderWebhook::dispatch($order, $webhookUrl, $webhookSecret);
                $this->saveOrderLog($order->id, 'webhook_dispatched', 'SendOrderWebhook job dispatched', $userId);
            }

            $this->alert('success', 'Order placed successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->saveOrderLog(null, 'order_failed', $e->getMessage(), $userId);
            $this->alert('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.order', [
            'products' => Product::query()
                ->where('status', 'active')
                ->when($this->search, fn ($q, $search) => $q->where('name', 'LIKE', "%$search%")->orWhere('sku', 'LIKE', "%$search%"))
                ->orderBy('created_at', 'desc')
                ->paginate($this->perPage),
        ]);
    }

    /**
     * Save order log into database
     */
    protected function saveOrderLog(?int $orderId, string $type, string $message, ?int $actorId = null)
    {
        if ($orderId) {
            $order = Order::find($orderId);
            if ($order) {
                $order->logs()->create([
                    'from_status' => $order->status,
                    'to_status' => $order->status,
                    'actor_id' => $actorId,
                    'reason' => $message,
                ]);
            }
        } else {
            Log::info("[SYSTEM LOG] {$type}: {$message}", ['actor_id' => $actorId]);
        }
    }
}

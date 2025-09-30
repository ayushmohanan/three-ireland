<?php

namespace App\Http\Controllers\Api\v1\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    // index
    public function index(Request $request)
    {
        //
    }

    // store details
    public function store(Request $request)
    {
        $mode = $request->input('mode', 'place');
        $userId = $request->input('user_id', $request->user()?->id ?? null);

        Log::info('Incoming order request', [
            'mode' => $mode,
            'user' => $userId,
            'lines' => $request->lines,
        ]);

        try {
            DB::beginTransaction();
            Log::info('DB transaction started for order creation');

            // Create order
            $order = Order::create([
                'status' => $mode === 'draft' ? 'draft' : 'placed',
                'user_id' => $userId ?? null,
            ]);

            // Log initial order creation
            $order->logs()->create([
                'from_status' => 'logs',
                'to_status' => $order->status,
                'actor_id' => $userId,
                'reason' => 'Order created via API',
            ]);

            Log::info('Order record created', [
                'order_id' => $order->id,
                'status' => $order->status,
            ]);

            foreach ($request->lines as $line) {
                Log::info('Processing order line', [
                    'product_id' => $line['product_id'],
                    'quantity' => $line['quantity'],
                ]);

                $product = Product::lockForUpdate()->find($line['product_id']);

                if (! $product) {
                    throw new \Exception("Product ID {$line['product_id']} not found");
                }

                if ($mode === 'place') {
                    Log::info('Checking stock availability', [
                        'product_id' => $product->id,
                        'available' => $product->stock_on_hand,
                        'requested' => $line['quantity'],
                    ]);

                    if ($product->stock_on_hand < $line['quantity']) {
                        Log::warning('Insufficient stock detected', [
                            'product_id' => $product->id,
                            'available' => $product->stock_on_hand,
                            'requested' => $line['quantity'],
                        ]);
                        throw new \Exception("Insufficient stock for {$product->name}");
                    }

                    $product->decrement('stock_on_hand', $line['quantity']);
                    Log::info('Stock decremented', [
                        'product_id' => $product->id,
                        'new_stock' => $product->stock_on_hand,
                    ]);
                }

                // Create order line
                $order->lines()->create([
                    'product_id' => $product->id,
                    'quantity' => $line['quantity'],
                    'unit_price' => $product->price,
                ]);

                Log::info('Order line created', [
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $line['quantity'],
                ]);
            }

            DB::commit();
            Log::info('DB transaction committed successfully', ['order_id' => $order->id]);

            return response()->json([
                'statusCode' => 201,
                'status' => 'success',
                'message' => $mode === 'draft'
                    ? 'Order draft created successfully'
                    : 'Order placed successfully',
                'data' => $order->load('lines.product', 'logs'),
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Order placement failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $request->all(),
            ]);

            return response()->json([
                'statusCode' => 422,
                'status' => 'failed',
                'message' => $e->getMessage(),
                'data' => [],
            ]);
        }
    }

    // show order detials
    public function show($id)
    {
        try {
            $order = Cache::remember("order_{$id}", 3600, function () use ($id) {
                return Order::with(['lines.product', 'logs'])->findOrFail($id);
            });

            return response()->json([
                'statusCode' => 200,
                'status' => 'success',
                'message' => 'Order retrieved successfully',
                'data' => $order,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'statusCode' => 404,
                'status' => 'failed',
                'message' => 'Order not found',
                'data' => [],
            ]);
        }
    }
}

<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateProducts extends Component
{
    use LivewireAlert;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|string|max:100|unique:products,sku')]
    public string $sku = '';

    #[Validate('required|numeric|min:0')]
    public float $price = 0;

    #[Validate('required|integer|min:0')]
    public int $stock_on_hand = 0;

    #[Validate('required|integer|min:0')]
    public int $reorder_threshold = 0;

    #[Validate('required|in:active,inactive')]
    public string $status = 'active';

    public ?string $tags = null;

    public function mount(): void
    {
        $this->authorize('create products');
    }

    public function createProduct(): void
    {
        $this->validate();

        $product = Product::create([
            'name' => $this->name,
            'sku' => $this->sku,
            'price' => $this->price,
            'stock_on_hand' => $this->stock_on_hand,
            'reorder_threshold' => $this->reorder_threshold,
            'status' => $this->status,
        ]);

        $this->flash('success', __('Product created successfully'));

        $this->redirect(route('admin.products.index'), true);

    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        return view('livewire.admin.products.create-products');
    }
}

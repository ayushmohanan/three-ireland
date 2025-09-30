<section class="w-full max-w-4xl mx-auto space-y-6">
    <x-page-heading>
        <x-slot:title>View Product</x-slot:title>
        <x-slot:subtitle>Details of product: {{ $product->name }}</x-slot:subtitle>
        <x-slot:buttons>
            <flux:button href="{{ route('admin.products.index') }}" variant="ghost" icon="arrow-left">
                Back to Products
            </flux:button>
        </x-slot:buttons>
    </x-page-heading>

    <div class="bg-white shadow rounded-lg p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <flux:label>Name</flux:label>
                <p class="mt-1 text-gray-700">{{ $product->name }}</p>
            </div>
            <div>
                <flux:label>SKU</flux:label>
                <p class="mt-1 text-gray-700 uppercase">{{ $product->sku }}</p>
            </div>

            <div>
                <flux:label>Price</flux:label>
                <p class="mt-1 text-gray-700">${{ number_format($product->price, 2) }}</p>
            </div>
            <div>
                <flux:label>Stock On Hand</flux:label>
                <p class="mt-1 text-gray-700">{{ $product->stock_on_hand }}</p>
            </div>

            <div>
                <flux:label>Reorder Threshold</flux:label>
                <p class="mt-1 text-gray-700">{{ $product->reorder_threshold }}</p>
            </div>
            <div>
                <flux:label>Status</flux:label>
                @if($product->status === 'active')
                <flux:badge variant="success" style="background:green;color:white">Active</flux:badge>
                @else
                <flux:badge variant="danger" style="background:red;color:white">Inactive</flux:badge>
                @endif
            </div>


            <div>
                <flux:label>Created At</flux:label>
                <p class="mt-1 text-gray-700">{{ $product->created_at->format('Y-m-d H:i') }}</p>
            </div>
            <div>
                <flux:label>Last Updated</flux:label>
                <p class="mt-1 text-gray-700">{{ $product->updated_at->format('Y-m-d H:i') }}</p>
            </div>
        </div>
    </div>
</section>

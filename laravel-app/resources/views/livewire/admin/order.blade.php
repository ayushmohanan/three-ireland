<section class="w-full">
    <x-page-heading title="Order Products" subtitle="Manage your orders" />

    <div class="flex items-center gap-2 mb-4">
        <flux:input wire:model.live="search" placeholder="Search products" />
        <flux:select wire:model.live="perPage">
            <flux:select.option value="10">10</flux:select.option>
            <flux:select.option value="25">25</flux:select.option>
            <flux:select.option value="50">50</flux:select.option>
        </flux:select>
    </div>

    <x-table>
        <x-slot:head>
            <x-table.row>
                <x-table.heading>ID</x-table.heading>
                <x-table.heading>Name</x-table.heading>
                <x-table.heading>SKU</x-table.heading>
                <x-table.heading>Price</x-table.heading>
                <x-table.heading>Stock</x-table.heading>
                <x-table.heading>Actions</x-table.heading>
            </x-table.row>
        </x-slot:head>
        <x-slot:body>
            @foreach($products as $product)
            <x-table.row>
                <x-table.cell>{{ $product->id }}</x-table.cell>
                <x-table.cell>{{ $product->name }}</x-table.cell>
                <x-table.cell>{{ $product->sku }}</x-table.cell>
                <x-table.cell>{{ $product->price }}</x-table.cell>
                <x-table.cell>{{ $product->stock_on_hand }}</x-table.cell>
                <x-table.cell>
                    <flux:button wire:click="addToCart({{ $product->id }})">Add to Cart</flux:button>
                </x-table.cell>
            </x-table.row>
            @endforeach
        </x-slot:body>
    </x-table>

    <div class="mt-6">
        <h2>Cart</h2>
        @if(count($cart) > 0)
        <table class="w-full">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody style="text-align: center;">
                @foreach($cart as $id => $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td style="text-align: center;">
                        <input type="number" min="1" wire:model.lazy="cart.{{ $id }}.quantity">

                    </td>
                    <td>{{ $item['unit_price'] }}</td>
                    <td>{{ $item['unit_price'] * $item['quantity'] }}</td>
                    <td>
                        <flux:button wire:click="removeFromCart({{ $id }})">Remove</flux:button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <hr class="my-4 border-gray-300" style="margin: 20px 0;">


        <flux:button wire:click="placeOrder">Place Order</flux:button>
        @else
        <p>No products in cart.</p>
        @endif
    </div>

    <div>{{ $products->links() }}</div>
</section>

<section class="w-full">
    <x-page-heading>
        <x-slot:title>
            {{ __('Products') }}
        </x-slot:title>
        <x-slot:subtitle>
            {{ __('Manage your products') }}
        </x-slot:subtitle>
        <x-slot:buttons>
            <div class="flex gap-2 items-center">
                @can('import products')
                <input type="file" wire:model="csvFile" accept=".csv"
                    class="block text-sm text-gray-700 border border-gray-300 rounded-md px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                <flux:button wire:click="uploadCsv" type="button" variant="primary">
                    {{ __('Import Products') }}
                </flux:button>
                @error('csvFile')
                <span class="text-red-500">{{ $message }}</span>
                @enderror
                @endcan

                @can('create products')
                <flux:button href="{{ route('admin.products.create') }}" variant="primary" icon="plus">
                    {{ __('Create Product') }}
                </flux:button>
                @endcan
            </div>
        </x-slot:buttons>
    </x-page-heading>



    <div class="flex items-center justify-between w-full mb-6 gap-2">
        <flux:input wire:model.live="search" placeholder="{{ __('global.search_here') }}" class="!w-auto" />
        <flux:spacer />
        <flux:select wire:model.live="perPage" class="!w-auto">
            <flux:select.option value="10">{{ __('global.10_per_page') }}</flux:select.option>
            <flux:select.option value="25">{{ __('global.25_per_page') }}</flux:select.option>
            <flux:select.option value="50">{{ __('global.50_per_page') }}</flux:select.option>
            <flux:select.option value="100">{{ __('global.100_per_page') }}</flux:select.option>
        </flux:select>
    </div>

    <x-table>
        <x-slot:head>
            <x-table.row>
                <x-table.heading>{{ __('global.id') }}</x-table.heading>
                <x-table.heading>{{ __('products.name') }}</x-table.heading>
                <x-table.heading>{{ __('products.sku') }}</x-table.heading>
                <x-table.heading>{{ __('products.price') }}</x-table.heading>
                <x-table.heading>{{ __('products.stock_on_hand') }}</x-table.heading>
                <x-table.heading>{{ __('products.status') }}</x-table.heading>
                <x-table.heading class="text-right">{{ __('global.actions') }}</x-table.heading>
            </x-table.row>
        </x-slot:head>
        <x-slot:body>
            @foreach($products as $product)
            <x-table.row wire:key="product-{{ $product->id }}">
                <x-table.cell>{{ $product->id }}</x-table.cell>
                <x-table.cell>{{ $product->name }}</x-table.cell>
                <x-table.cell>{{ $product->sku }}</x-table.cell>
                <x-table.cell>{{ $product->price }}</x-table.cell>
                <x-table.cell>{{ $product->stock_on_hand }}</x-table.cell>
                <x-table.cell>
                    @if($product->status === 'active')
                    <flux:badge size="sm" variant="success" style="background:green;color:aliceblue">{{ __('Active') }}
                    </flux:badge>
                    @else
                    <flux:badge size="sm" variant="danger" style="background:red;color:aliceblue">{{ __('Inactive') }}
                    </flux:badge>
                    @endif
                </x-table.cell>
                <x-table.cell class="gap-2 flex justify-end">
                    <flux:button href="{{ route('admin.products.show', $product) }}" size="sm" variant="ghost">
                        {{ __('global.view') }}
                    </flux:button>

                    @can('update products')
                    <flux:button href="{{ route('admin.products.edit', $product) }}" size="sm">
                        {{ __('global.edit') }}
                    </flux:button>
                    @endcan

                    @can('delete products')
                    <flux:modal.trigger name="delete-product-{{ $product->id }}">
                        <flux:button size="sm" variant="danger">{{ __('global.delete') }}</flux:button>
                    </flux:modal.trigger>
                    <flux:modal name="delete-product-{{ $product->id }}"
                        class="min-w-[22rem] space-y-6 flex flex-col justify-between">
                        <div>
                            <flux:heading size="lg">{{ __('Delete Product') }}?</flux:heading>
                            <flux:subheading>
                                <p>{{ __('You are about to delete this product.') }}</p>
                                <p>{{ __('global.this_action_is_irreversible') }}</p>
                            </flux:subheading>
                        </div>
                        <div class="flex gap-2 !mt-auto mb-0">
                            <flux:modal.close>
                                <flux:button variant="ghost">
                                    {{ __('global.cancel') }}
                                </flux:button>
                            </flux:modal.close>
                            <flux:spacer />
                            <flux:button type="submit" variant="danger"
                                wire:click.prevent="deleteProduct('{{ $product->id }}')">
                                {{ __('Delete Product') }}
                            </flux:button>
                        </div>
                    </flux:modal>
                    @endcan
                </x-table.cell>
            </x-table.row>
            @endforeach
        </x-slot:body>
    </x-table>

    <div>
        {{ $products->links() }}
    </div>
</section>

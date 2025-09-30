<section class="w-full">
    <x-page-heading title="Product Order List" subtitle="View all customer orders" />

    <div class="flex items-center gap-2 mb-4">
        <flux:input wire:model.live="search" placeholder="Search by ID, user, or status" />
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
                <x-table.heading>User</x-table.heading>
                <x-table.heading>Status</x-table.heading>
                <x-table.heading>Total</x-table.heading>
                <x-table.heading>Created</x-table.heading>
            </x-table.row>
        </x-slot:head>

        <x-slot:body>
            @forelse($orders as $order)
            <x-table.row>
                <x-table.cell>#{{ $order->id }}</x-table.cell>
                <x-table.cell>{{ $order->user?->name ?? 'System' }}</x-table.cell>
                <x-table.cell>{{ ucfirst($order->status) }}</x-table.cell>
                <x-table.cell>
                    € {{ $order->lines->sum(fn($l) => $l->unit_price * $l->quantity) }}
                </x-table.cell>
                <x-table.cell>{{ $order->created_at->format('Y-m-d H:i') }}</x-table.cell>

            </x-table.row>
            @empty
            <x-table.row>
                <x-table.cell colspan="6" class="text-center">No orders found</x-table.cell>
            </x-table.row>
            @endforelse
        </x-slot:body>
    </x-table>

    <div class="mt-4">{{ $orders->links() }}</div>
</section>

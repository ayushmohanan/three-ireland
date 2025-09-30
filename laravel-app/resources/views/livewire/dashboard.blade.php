<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl min-h-[40rem] bg-gray-900">
    <div class="grid auto-rows-min gap-4 md:grid-cols-3">
        <!-- Total Orders -->
        <div
            class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gray-800 text-black flex flex-col justify-center items-center">
            <h3 class="text-lg font-semibold">Total Orders</h3>
            <p class="text-3xl font-bold text-indigo-400">{{ $totalOrders }}</p>
        </div>

        <!-- Pending Orders -->
        <div
            class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gray-800 text-black flex flex-col justify-center items-center">
            <h3 class="text-lg font-semibold">Pending Orders</h3>
            <p class="text-3xl font-bold text-yellow-400">{{ $pendingOrders }}</p>
        </div>

        <!-- Fulfilled Orders -->
        <div
            class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gray-800 text-black flex flex-col justify-center items-center">
            <h3 class="text-lg font-semibold">Fulfilled Orders</h3>
            <p class="text-3xl font-bold text-green-400">{{ $fulfilledOrders }}</p>
        </div>
    </div>

    <!-- Large box for customers & products -->
    <div
        class="relative h-full flex-1 rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gray-800 text-black p-6 flex flex-col justify-center">
        <div class="grid grid-cols-2 gap-6">
            <div class="flex flex-col items-center">
                <h3 class="text-lg font-semibold">Total Customers</h3>
                <p class="text-3xl font-bold text-blue-400">{{ $totalCustomers }}</p>
            </div>
            <div class="flex flex-col items-center">
                <h3 class="text-lg font-semibold">Total Products</h3>
                <p class="text-3xl font-bold text-purple-400">{{ $totalProducts }}</p>
            </div>
        </div>
    </div>
</div>

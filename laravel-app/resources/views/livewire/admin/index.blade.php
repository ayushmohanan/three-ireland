<section class="w-full">
    <x-page-heading title="Dashboard" subtitle="Overview of your store" />

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <div class="bg-gray-800 rounded-xl shadow p-6 text-center">
            <h3 class="text-lg font-semibold text-gray-200">Total Orders</h3>
            <p class="text-2xl font-bold text-indigo-400">{{ $totalOrders }}</p>
        </div>

        <div class="bg-gray-800 rounded-xl shadow p-6 text-center">
            <h3 class="text-lg font-semibold text-gray-200">Pending Orders</h3>
            <p class="text-2xl font-bold text-yellow-400">{{ $pendingOrders }}</p>
        </div>

        <div class="bg-gray-800 rounded-xl shadow p-6 text-center">
            <h3 class="text-lg font-semibold text-gray-200">Fulfilled Orders</h3>
            <p class="text-2xl font-bold text-green-400">{{ $fulfilledOrders }}</p>
        </div>

        <div class="bg-gray-800 rounded-xl shadow p-6 text-center">
            <h3 class="text-lg font-semibold text-gray-200">Total Customers</h3>
            <p class="text-2xl font-bold text-blue-400">{{ $totalCustomers }}</p>
        </div>

        <div class="bg-gray-800 rounded-xl shadow p-6 text-center">
            <h3 class="text-lg font-semibold text-gray-200">Total Products</h3>
            <p class="text-2xl font-bold text-purple-400">{{ $totalProducts }}</p>
        </div>
    </div>
</section>

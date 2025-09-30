<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{
    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        return view('livewire.admin.index', [
            'totalOrders' => Order::count(),
            'pendingOrders' => Order::where('status', 'placed')->count(),
            'fulfilledOrders' => Order::where('status', 'fulfilled')->count(),
            'totalCustomers' => User::count(), // assumes User model = customers
            'totalProducts' => Product::count(),
        ]);
    }
}

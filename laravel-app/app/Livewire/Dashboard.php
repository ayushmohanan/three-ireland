<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Dashboard extends Component
{
    #[Layout('components.layouts.app')]
    public function render(): View
    {
        // Fetch data for dashboard boxes
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'placed')->count();
        $fulfilledOrders = Order::where('status', 'fulfilled')->count();
        $totalCustomers = User::count();
        $totalProducts = Product::count();

        return view('livewire.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'fulfilledOrders',
            'totalCustomers',
            'totalProducts'
        ));
    }
}

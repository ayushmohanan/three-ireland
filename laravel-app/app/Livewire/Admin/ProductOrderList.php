<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class ProductOrderList extends Component
{
    use WithPagination;

    public string $search = '';

    public int $perPage = 10;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $orders = Order::query()
            ->with(['user', 'lines.product'])
            ->when($this->search, function ($q, $search) {
                $q->where('id', $search)
                    ->orWhereHas('user', fn ($uq) => $uq->where('name', 'LIKE', "%$search%"))
                    ->orWhere('status', 'LIKE', "%$search%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.order-list', compact('orders'));
    }
}

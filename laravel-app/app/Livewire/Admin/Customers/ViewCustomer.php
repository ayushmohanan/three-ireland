<?php

namespace App\Livewire\Admin\Customers;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ViewCustomer extends Component
{
    public Customer $customer;

    public function mount(Customer $customer): void
    {
        $this->authorize('view customers');

        $this->customer = $customer;
    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        return view('livewire.admin.customers.view-customer');
    }
}

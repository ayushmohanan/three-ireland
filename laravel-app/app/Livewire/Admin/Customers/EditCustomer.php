<?php

namespace App\Livewire\Admin\Customers;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportRedirects\HandlesRedirects;

class EditCustomer extends Component
{
    use HandlesRedirects;
    use LivewireAlert;

    public Customer $customer;

    #[Validate(['required', 'string', 'max:255'])]
    public string $name = '';

    #[Validate(['required', 'string', 'email', 'max:255'])]
    public string $email = '';

    public ?string $phone = null;

    public function mount(Customer $customer): void
    {
        $this->authorize('update customers');

        $this->customer = $customer;
        $this->name = $customer->name;
        $this->email = $customer->email;
        $this->phone = $customer->phone;
    }

    public function updateCustomer(): void
    {
        // $this->authorize('update customers');

        $this->validate();

        $this->customer->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);

        $this->flash('success', __('Customer updated successfully!'));

        $this->redirect(route('admin.customers.index'), true);
    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        return view('livewire.admin.customers.edit-customer');
    }
}

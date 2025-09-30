<?php

namespace App\Livewire\Admin\Customers;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class CreateCustomer extends Component
{
    use LivewireAlert;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|email|max:255|unique:customers,email')]
    public string $email = '';

    public $phone;

    public function mount(): void
    {
        $this->authorize('create customers');
    }

    public function createCustomer(): void
    {
        $this->validate();

        $user = Customer::query()->create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,

        ]);

        $this->flash('success', __('Customer created successfully'));

        $this->redirect(route('admin.customers.index'), true);

    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        return view('livewire.admin.customers.create-customer', [
            'roles' => Role::all(),
            'locales' => [
                'en' => 'English',
            ],
        ]);
    }
}

<?php

namespace App\Livewire\Admin;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Session;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Customers extends Component
{
    use LivewireAlert;
    use WithPagination;

    /** @var array<string,string> */
    protected $listeners = [
        'userDeleted' => '$refresh',
    ];

    #[Session]
    public int $perPage = 10;

    /** @var array<int,string> */
    public array $searchableFields = ['name', 'email'];

    #[Url]
    public string $search = '';

    public ?string $role = null;

    public function mount(): void
    {
        $this->authorize('view users');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function deleteUser(string $customerId): void
    {

        $this->authorize('delete users');

        $customer = Customer::query()->where('id', $customerId)->firstOrFail();

        $customer->delete();

        $this->alert('success', 'Customer deleted successfully');

        $this->dispatch('userDeleted');
    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        return view('livewire.admin.customers', [
            'users' => Customer::query()
                ->when($this->search, function ($query, $search): void {
                    $query->whereAny($this->searchableFields, 'LIKE', "%$search%");
                })
                ->get(),
        ]);
    }
}

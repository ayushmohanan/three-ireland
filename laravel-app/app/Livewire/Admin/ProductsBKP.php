<?php

namespace App\Livewire\Admin;

use App\Jobs\ImportProductsJob;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Session;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Products extends Component
{
    use LivewireAlert, WithFileUploads, WithPagination;

    #[Session]
    public int $perPage = 10;

    #[Url]
    public string $search = '';

    public string $sortBy = 'created_at';

    public string $sortDirection = 'desc';

    public $csvFile;

    /** @var array<string> */
    protected $listeners = ['productDeleted' => '$refresh'];

    public function mount(): void
    {
        $this->authorize('view products');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // Sorting function
    public function sortBy(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    // Delete product
    public function deleteProduct(string $productId): void
    {
        $this->authorize('delete products');

        $product = Product::findOrFail($productId);
        $product->delete();

        $this->alert('success', 'Product deleted successfully');
        $this->dispatch('productDeleted');
    }

    // CSV Upload
    public function uploadCsv(): void
    {
        $this->validate([
            'csvFile' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $filename = 'products_'.now()->format('Ymd_His').'_'.Str::random(6).'.csv';
        $path = $this->csvFile->storeAs('imports', $filename);

        ImportProductsJob::dispatch($path);

        $this->alert('success', 'CSV uploaded successfully. Products will be imported in background.');
        $this->csvFile = null;
    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        $products = Product::query()
            ->when($this->search, function ($query, $search) {
                $query->where('name', 'LIKE', "%$search%")
                    ->orWhere('sku', 'LIKE', "%$search%")
                    ->orWhere('status', 'LIKE', "%$search%")
                    ->orWhere('tags', 'LIKE', "%$search%");
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.products', [
            'products' => $products,
        ]);
    }
}

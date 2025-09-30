<section class="w-full">
    <x-page-heading>
        <x-slot:title>{{ __('Edit Product') }}</x-slot:title>
        <x-slot:subtitle>
            {{ __('Update the product details.') }}
        </x-slot:subtitle>
    </x-page-heading>

    <x-form wire:submit="updateProduct" class="space-y-6">
        <flux:input wire:model.live="name" label="Name *" id="name" />

        <flux:input wire:model.live="sku" label="SKU *" id="sku" />

        <flux:input wire:model.live="price" type="number" step="0.01" label="Price *" />

        <flux:input wire:model.live="stock_on_hand" type="number" label="Stock On Hand *" />

        <flux:input wire:model.live="reorder_threshold" type="number" label="Reorder Threshold *" />

        <flux:select wire:model.live="status" label="Status *">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </flux:select>

        <flux:input wire:model.live="tags" label="Tags (comma separated)" />

        <flux:button type="submit" icon="save" variant="primary">
            {{ __('Update Product') }}
        </flux:button>
    </x-form>
</section>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const nameInput = document.getElementById("name");
    const skuInput = document.getElementById("sku");

    let manuallyEdited = false;

    skuInput.addEventListener("input", () => {
        manuallyEdited = true;
        skuInput.value = skuInput.value.toUpperCase();
        skuInput.dispatchEvent(new Event("input"));
    });

    nameInput.addEventListener("input", () => {
        if (!manuallyEdited) {
            let slug = nameInput.value
                .toUpperCase()
                .replace(/[^A-Z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');

            if (slug.length > 0) {
                skuInput.value = slug;
                skuInput.dispatchEvent(new Event("input"));
            }
        }
    });
});
</script>
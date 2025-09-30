<section class="w-full">
    <x-page-heading>
        <x-slot:title>
            {{ 'Edit Customer' }}
        </x-slot:title>
        <x-slot:subtitle>
            {{ 'Edit Customer in the app' }}
        </x-slot:subtitle>
    </x-page-heading>

    <x-form wire:submit="updateCustomer" class="space-y-6">
        <flux:input wire:model.live="name" label="{{ __('users.name') }}" />

        <flux:input wire:model.live="email" label="{{ __('users.email') }}" />

        <flux:input wire:model.live="phone" label="{{ __('users.phone') }}" />


        <flux:button type="submit" icon="save" variant="primary">
            {{ 'Update Customer' }}
        </flux:button>
    </x-form>

</section>
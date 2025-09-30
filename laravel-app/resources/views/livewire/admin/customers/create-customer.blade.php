<section class="w-full">
    <x-page-heading>
        <x-slot:title>{{ __('Create Customer') }}</x-slot:title>
        <x-slot:subtitle>
            {{ __('Create a new customer in the app') }}
        </x-slot:subtitle>
    </x-page-heading>

    <x-form wire:submit="createCustomer" class="space-y-6">

        <flux:input wire:model.live="name" label="Name *" />
        <flux:input wire:model.live="email" label="E-mail *" />
        <flux:input wire:model.live="phone" label="Phone" />

        <flux:button type="submit" icon="save" variant="primary">
            {{'Create Customer' }}
        </flux:button>
    </x-form>

</section>

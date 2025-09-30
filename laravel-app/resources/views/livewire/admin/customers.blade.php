<section class="w-full">
    <x-page-heading>
        <x-slot:title>
            {{ 'Customers' }}
        </x-slot:title>
        <x-slot:subtitle>
            {{ 'Manage your customers' }}
        </x-slot:subtitle>
        <x-slot:buttons>
            @can('create customers')
            <flux:button href="{{ route('admin.customers.create') }}" variant="primary" icon="plus">
                {{ 'Create Customer' }}
            </flux:button>
            @endcan
        </x-slot:buttons>
    </x-page-heading>

    <div class="flex items-center justify-between w-full mb-6 gap-2">
        <flux:input wire:model.live="search" placeholder="{{ __('global.search_here') }}" class="!w-auto" />
        <flux:spacer />
    </div>

    <x-table>
        <x-slot:head>
            <x-table.row>
                <x-table.heading>{{ __('global.id') }}</x-table.heading>
                <x-table.heading>{{ __('users.name') }}</x-table.heading>
                <x-table.heading>{{ __('users.email') }}</x-table.heading>
                <x-table.heading class="text-right">{{ __('global.actions') }}</x-table.heading>
            </x-table.row>
        </x-slot:head>
        <x-slot:body>
            @foreach($users as $user)
            <x-table.row wire:key="user-{{ $user->id }}">
                <x-table.cell>{{ $user->id }}</x-table.cell>
                <x-table.cell>{{ $user->name }}</x-table.cell>
                <x-table.cell>{{ $user->email }}</x-table.cell>
                <x-table.cell class="gap-2 flex justify-end">

                    <flux:button href="{{ route('admin.customers.show', $user) }}" size="sm" variant="ghost">
                        {{ __('global.view') }}
                    </flux:button>


                    @can('update customers')
                    <flux:button href="{{ route('admin.customers.edit', $user) }}" size="sm">
                        {{ __('global.edit') }}
                    </flux:button>
                    @endcan

                    @can('delete customers')
                    <flux:modal.trigger name="delete-profile-{{ $user->id }}">
                        <flux:button size="sm" variant="danger">{{ __('global.delete') }}</flux:button>
                    </flux:modal.trigger>
                    <flux:modal name="delete-profile-{{ $user->id }}"
                        class="min-w-[22rem] space-y-6 flex flex-col justify-between">
                        <div>
                            <flux:heading size="lg">{{ 'Delete Customer' }}?</flux:heading>
                            <flux:subheading>
                                <p>{{ 'Your about to delete the customer' }}</p>
                                <p>{{ __('global.this_action_is_irreversible') }}</p>
                            </flux:subheading>
                        </div>
                        <div class="flex gap-2 !mt-auto mb-0">
                            <flux:modal.close>
                                <flux:button variant="ghost">
                                    {{ __('global.cancel') }}
                                </flux:button>
                            </flux:modal.close>
                            <flux:spacer />
                            <flux:button type="submit" variant="danger"
                                wire:click.prevent="deleteUser('{{ $user->id }}')">
                                {{ 'Delete Customer' }}
                            </flux:button>
                        </div>
                    </flux:modal>
                    @endcan
                </x-table.cell>
            </x-table.row>
            @endforeach
        </x-slot:body>
    </x-table>
</section>

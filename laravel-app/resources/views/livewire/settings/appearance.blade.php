<div class="flex flex-col items-start">
    <x-page-heading>
        <x-slot:title>{{ __('settings.title') }}</x-slot:title>
        <x-slot:subtitle>{{ __('settings.subtitle') }}</x-slot:subtitle>
    </x-page-heading>

    <x-settings.layout :heading="__('settings.appearance')" :subheading=" __('settings.update_your_settings_appearance')">
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
            <flux:radio value="light" icon="sun">{{ __('settings.light') }}</flux:radio>
            <flux:radio value="dark" icon="moon">{{ __('settings.dark') }}</flux:radio>
            <flux:radio value="system" icon="computer-desktop">{{ __('settings.system') }}</flux:radio>
        </flux:radio.group>
    </x-settings.layout>
</div>

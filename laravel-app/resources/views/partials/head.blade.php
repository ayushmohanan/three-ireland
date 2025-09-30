<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>
    @if(!empty($title))
    {{ $title }} | {{ config('app.name') }}
    @else
    {{ config('app.name') }}
    @endif
</title>

<!-- CSRF Token (required for Livewire & forms) -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

<!-- Styles -->
@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
@livewireStyles
<!-- ✅ required for Livewire -->

<!-- Scripts -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<x-livewire-alert::scripts />
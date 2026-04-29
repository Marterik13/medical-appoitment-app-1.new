@props(['name'])

<div x-show="tab === '{{ $name }}'" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform scale-95"
     x-transition:enter-end="opacity-100 transform scale-100"
     style="display: none;">
    {{ $slot }}
</div>
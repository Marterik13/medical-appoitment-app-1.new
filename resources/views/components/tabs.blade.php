@props(['active'])

<div x-data="{ tab: '{{ $active }}' }">
    <div class="border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500">
            {{ $header }}
        </ul>
    </div>

    <div class="mt-4">
        {{ $slot }}
    </div>
</div>
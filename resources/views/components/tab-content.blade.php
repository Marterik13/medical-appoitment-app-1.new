@props ( ['tab', 'error' => falsel)
<div x-show="tab === '{{ $tab }}'" style="display: none">
{{ $slot }}
</div>
@props([
    'label' => '',
    'value' => null
])

<dt>{{ $label }}</dt>
<dd>
    @if($value !== null)
        {{ $value }}
    @else
        {{ $slot }}
    @endif
</dd>
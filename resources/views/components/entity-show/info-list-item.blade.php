@props([
    'label' => '',
    'value' => null
])

<dt>{{ __($label) }}</dt>
<dd>
    @if($value !== null)
        {{ $value }}
    @else
        {{ $slot }}
    @endif
</dd>
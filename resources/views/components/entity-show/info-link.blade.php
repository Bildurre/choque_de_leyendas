@props([
    'href' => '#',
    'iconSize' => 'xs',
    'iconName' => 'link'
])

<a href="{{ $href }}" class="info-link">
    {{ $slot }}
    <x-icon :name="$iconName" :size="$iconSize" />
</a>
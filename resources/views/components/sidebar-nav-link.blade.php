@props([
  'route' => '#',
  'active' => false,
  'iconType' => 'dice',
  'icon' => null,
  'color1' => null,
  'color2' => null,
  'color3' => null
])

<a href="{{ $route }}"
  {{ $attributes->merge(['class' => 'sidebar-nav-link ' . ($active ? 'active' : '')]) }}>
  @if($icon)
    @if ($iconType == 'dice')
      <x-game-dice variant="{{ $icon }}" size="md" :color1="$color1" :color2="$color2" :color3="$color3"/>
    @else
      <x-icon name="{{ $icon }}" />
    @endif
  @endif
  {{ $slot }}
</a>
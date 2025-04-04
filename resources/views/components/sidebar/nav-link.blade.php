@props([
  'route' => '#',
  'active' => false,
  'icon' => null
])

<a href="{{ $route }}"
  {{ $attributes->merge(['class' => 'sidebar-nav-link ' . ($active ? 'active' : '')]) }}>
  @if($icon)
    <x-game-dice variant="{{ $icon }}" size="sm"/>
  @endif
  {{ $slot }}
</a>
@props([
  'href' => '#',
  'icon' => null
])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'dropdown-item']) }}>
  @if($icon)
    <x-icon :name="$icon" size="sm" class="dropdown-item__icon" />
  @endif
  
  <span class="dropdown-item__text">{{ $slot }}</span>
</a>
@props([
  'id',
  'active' => false,
  'href' => '#',
  'icon' => null,
  'count' => null
])

<a href="{{ $href }}" class="tabs__item {{ $active ? 'tabs__item--active' : '' }}" id="tab-{{ $id }}">
  @if($icon)
    <x-icon :name="$icon" size="sm" class="tabs__icon" />
  @endif
  
  <span class="tabs__text">
    {{ $slot }}
    @if(!is_null($count))
      <span class="tabs__count">({{ $count }})</span>
    @endif
  </span>
</a>
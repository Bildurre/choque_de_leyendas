{{-- resources/views/components/tab-item.blade.php --}}
@props([
  'id',
  'active' => false,
  'href' => '#',
  'icon' => null
])

<a href="{{ $href }}" class="tabs__item {{ $active ? 'tabs__item--active' : '' }}" id="tab-{{ $id }}">
  @if($icon)
    <x-icon :name="$icon" size="sm" class="tabs__icon" />
  @endif
  
  <span class="tabs__text">{{ $slot }}</span>
</a>
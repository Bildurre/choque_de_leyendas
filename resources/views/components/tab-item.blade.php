@props([
  'id',
  'active' => false,
  'href' => '#',
  'icon' => null,
  'count' => null,
  'useInitial' => false,
])

@php
  $initial = $useInitial ? mb_strtoupper(mb_substr(trim($slot), 0, 1)) : null;
@endphp

<a 
  href="{{ $href }}" 
  class="tabs__item {{ $active ? 'tabs__item--active' : '' }}"
  data-tab-id="{{ $id }}"
>
  @if($useInitial && $initial)
    <span class="tabs__initial">{{ $initial }}</span>
  @elseif($icon)
    <x-icon :name="$icon" size="sm" class="tabs__icon" />
  @endif
  
  <span class="tabs__text">{{ $slot }}</span>
  
  @if($count !== null)
    <span class="tabs__count">({{ $count }})</span>
  @endif
</a>
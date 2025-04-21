@props([
  'icon' => null,
  'count' => 0,
  'label' => 'items',
  'iconSize' => '1rem'
  ])

<div class="info-item">
  @if ($icon)
    <x-icon :name="$icon" :size="$iconSize" />
  @endif
  <span class="info-item__label">{{ $label }}</span>
</div>
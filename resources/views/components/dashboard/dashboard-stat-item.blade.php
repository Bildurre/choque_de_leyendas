@props([
  'label' => '',
  'value' => '',
  'icon' => null,
  'color' => null,
  'size' => 'default', // default, small, large
])

<div class="dashboard-stat-item dashboard-stat-item--{{ $size }}">
  @if($icon)
    <div class="dashboard-stat-item__icon" @if($color) style="color: {{ $color }}" @endif>
      <x-icon :name="$icon" size="md" />
    </div>
  @endif
  <div class="dashboard-stat-item__content">
    <div class="dashboard-stat-item__value">{{ $value }}</div>
    <div class="dashboard-stat-item__label">{{ $label }}</div>
  </div>
</div>
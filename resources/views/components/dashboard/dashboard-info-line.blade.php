@props([
  'label' => '',
  'value' => '',
  'color' => null,
  'showBar' => false,
  'percentage' => 0,
])

<div class="dashboard-info-line">
  <div class="dashboard-info-line__header">
    <span class="dashboard-info-line__label">{{ $label }}</span>
    <span class="dashboard-info-line__value">
      {{ $value }}
    </span>
  </div>
  @if($showBar)
    <div class="dashboard-info-line__bar">
      <div 
        class="dashboard-info-line__bar-fill" 
        style="width: {{ $percentage }}%; @if($color) background-color: {{ $color }} @endif"
      ></div>
    </div>
  @endif
</div>
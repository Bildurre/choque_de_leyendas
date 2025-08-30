
@props([
  'label',
  'value',
  'variant' => 'default'
])

<div class="preview-management-stat-item preview-management-stat-item--{{ $variant }}">
  <span class="preview-management-stat-label">{{ $label }}:</span>
  <span class="preview-management-stat-value">{{ $value }}</span>
</div>
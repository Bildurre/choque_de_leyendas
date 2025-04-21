@props(['label', 'value', 'variant' => null])

<div class="attribute-item {{ $variant ? "attribute-item--{$variant}" : '' }}">
  <span class="attribute-item__label">{{ $label }}</span>
  <span class="attribute-item__value">{{ $value }}</span>
</div>
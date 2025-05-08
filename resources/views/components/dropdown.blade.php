@props([
  'label' => '',
  'icon' => null,
  'variant' => 'primary',
  'size' => 'md',
  'align' => 'right'
])

<div {{ $attributes->merge(['class' => 'dropdown']) }}>
  <button type="button" class="btn btn--{{ $variant }} btn--{{ $size }} dropdown__toggle">
    @if($icon)
      <x-icon :name="$icon" size="sm" class="btn__icon" />
    @endif
    
    <span class="btn__text">{{ $label }}</span>
    <x-icon name="chevron-down" size="sm" class="dropdown__caret" />
  </button>
  
  <div class="dropdown__menu dropdown__menu--{{ $align }}">
    {{ $slot }}
  </div>
</div>
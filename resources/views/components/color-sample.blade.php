@props(['color', 'label' => null, 'size' => 'md'])

<div class="color-sample-container">
  <div class="color-sample color-sample--{{ $size }}" style="background-color: {{ $color }}"></div>
  @if($label)
    <span class="color-sample__label">{{ $label }}</span>
  @endif
</div>
@props([
  'title',
  'class' => null
])

<div class="preview-management-card {{ $class }}">
  <h3 class="preview-management-card__title">{{ $title }}</h3>
  <div class="preview-management-card__content">
    {{ $slot }}
  </div>
</div>
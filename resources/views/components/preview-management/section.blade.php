@props([
  'title'
])

<div class="preview-management-section">
  <h2 class="preview-management-section__title">{{ $title }}</h2>
  <div class="preview-management-section__grid">
    {{ $slot }}
  </div>
</div>
@props(['title' => 'Descripción'])

<div class="description-section">
  <h4>{{ $title }}</h4>
  <div class="description-content">
    {{ $slot }}
  </div>
</div>
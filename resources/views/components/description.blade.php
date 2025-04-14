@props(['title' => null])

<div class="description-section">
  @if ($title)
    <h3>{{ $title }}</h3>
  @endif
  <div class="description-content">
    {{ $slot }}
  </div>
</div>
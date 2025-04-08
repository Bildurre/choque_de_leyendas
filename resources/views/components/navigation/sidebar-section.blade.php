@props(['title'])

<div class="sidebar-section">
  <span class="sidebar-section-title">{{ $title }}</span>
  <ul class="sidebar-section-list">
    {{ $slot }}
  </ul>
</div>
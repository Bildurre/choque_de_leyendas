@props(['title' => null])

<div class="sidebar-section">
  @if ($title)
    <span class="sidebar-section-title">{{ $title }}</span>    
  @endif
  <ul class="sidebar-section-list">
    {{ $slot }}
  </ul>
</div>
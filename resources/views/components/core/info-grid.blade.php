@props(['columns' => 2])

<div class="info-grid info-grid--{{ $columns }}-cols">
  {{ $slot }}
</div>
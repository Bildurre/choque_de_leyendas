@props(['src', 'alt', 'size' => 'md'])

<div class="detail-image detail-image--{{ $size }}">
  <img src="{{ $src }}" alt="{{ $alt }}" class="detail-image__img">
</div>
@props(['title' => null, 'noPadding' => false])

<div class="detail-section {{ $noPadding ? 'detail-section--no-padding' : '' }}">
  @if($title)
    <h3 class="detail-section__title">{{ $title }}</h3>
  @endif
  
  <div class="detail-section__content">
    {{ $slot }}
  </div>
</div>
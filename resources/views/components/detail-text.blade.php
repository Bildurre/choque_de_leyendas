@props(['content', 'isHtml' => false])

<div class="detail-text">
  @if($isHtml)
    {!! $content !!}
  @else
    <p>{{ $content }}</p>
  @endif
</div>
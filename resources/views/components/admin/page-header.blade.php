@props(['title'])

<div class="page-header">
  <h1 class="page-title">{{ $title }}</h1>
  
  @if(isset($actions))
    <div class="page-header__actions">
      {{ $actions }}
    </div>
  @endif
</div>
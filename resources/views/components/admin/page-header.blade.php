@props(['title'])

<div class="page-header">
  <div class="page-header__wrapper">
    <h1 class="page-header__title">{{ $title }}</h1>

    @if(isset($actionButtons))
      <div class="page-header__action-buttons">
        {{ $actionButtons }}
      </div>
    @endif
    
    @if(isset($actions))
      <div class="page-header__actions">
        {{ $actions }}
      </div>
    @endif
  </div>
</div>
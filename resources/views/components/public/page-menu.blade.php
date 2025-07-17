@props(['activeClass' => 'nav-link--active'])

@php
  $rootPages = \App\Models\Page::root()
    ->published()
    ->notHome()
    ->orderBy('order')
    ->get();
    
  // Helper function to check if any child is active
  $hasActiveChild = function($page) {
    $publishedChildren = $page->children()->published()->get();
    
    foreach($publishedChildren as $child) {
      if(request()->url() == route('content.page', $child)) {
        return true;
      }
    }
    
    return false;
  };
@endphp

@if($rootPages->isNotEmpty())
  @foreach($rootPages as $page)
    @php
      $publishedChildren = $page->children()->published()->orderBy('order')->get();
      $hasPublishedChildren = $publishedChildren->count() > 0;
      $isCurrentPage = request()->url() == route('content.page', $page);
      $childIsActive = $hasPublishedChildren && $hasActiveChild($page);
      $shouldShowActive = $isCurrentPage || $childIsActive;
    @endphp
    
    <li class="nav-item {{ $hasPublishedChildren ? 'nav-item--has-children' : '' }} {{ $childIsActive ? 'nav-item--has-active-child' : '' }}">
      <a href="{{ route('content.page', $page) }}" 
         class="nav-link {{ $shouldShowActive ? $activeClass : '' }} {{ $childIsActive && !$isCurrentPage ? 'nav-link--active-parent' : '' }}">
        {{ $page->title }}
        @if($hasPublishedChildren)
          <x-icon name="chevron-down" size="sm" class="nav-link__icon" />
        @endif
      </a>
      
      @if($hasPublishedChildren)
        <div class="nav-dropdown">
          <ul class="nav-dropdown__list">
            @foreach($publishedChildren as $childPage)
              <li class="nav-dropdown__item">
                <a href="{{ route('content.page', $childPage) }}" 
                   class="nav-dropdown__link {{ request()->url() == route('content.page', $childPage) ? $activeClass : '' }}">
                  {{ $childPage->title }}
                </a>
              </li>
            @endforeach
          </ul>
        </div>
      @endif
    </li>
  @endforeach
@endif
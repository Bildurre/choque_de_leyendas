@props(['activeClass' => 'nav-link--active'])

@php
  $rootPages = \App\Models\Page::root()
    ->published()
    ->notHome()
    ->orderBy('order')
    ->get();
@endphp

@if($rootPages->isNotEmpty())
  @foreach($rootPages as $page)
    @php
      $publishedChildren = $page->children()->published()->orderBy('order')->get();
      $hasPublishedChildren = $publishedChildren->count() > 0;
    @endphp
    
    <li class="nav-item {{ $hasPublishedChildren ? 'nav-item--has-children' : '' }}">
      <a href="{{ route('content.page', $page) }}" 
         class="nav-link {{ request()->url() == route('content.page', $page) ? $activeClass : '' }}">
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
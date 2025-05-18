@props(['activeClass' => 'nav-link--active'])

@php
  // Obtenemos todas las páginas raíz publicadas ordenadas por orden
  $rootPages = \App\Models\Page::root()
    ->published()
    ->orderBy('order')
    ->get();
@endphp

@if($rootPages->isNotEmpty())
  @foreach($rootPages as $page)
    @php
      // Verificamos si hay hijas publicadas, no solo hijas en general
      $publishedChildren = $page->children()->published()->count();
      $hasPublishedChildren = $publishedChildren > 0;
    @endphp
    
    <li class="nav-item {{ $hasPublishedChildren ? 'nav-item--has-children' : '' }}">
      <a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('content.page', $page, false)) }}" 
         class="nav-link {{ request()->url() == LaravelLocalization::getLocalizedURL(app()->getLocale(), route('content.page', $page, false)) ? $activeClass : '' }}">
        {{ $page->title }}
        @if($hasPublishedChildren)
          <x-icon name="chevron-down" size="sm" class="nav-link__icon" />
        @endif
      </a>
      
      @if($hasPublishedChildren)
        <div class="nav-dropdown">
          <ul class="nav-dropdown__list">
            @foreach($page->children()->published()->orderBy('order')->get() as $childPage)
              <li class="nav-dropdown__item">
                <a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('content.page', $childPage, false)) }}" 
                   class="nav-dropdown__link {{ request()->url() == LaravelLocalization::getLocalizedURL(app()->getLocale(), route('content.page', $childPage, false)) ? $activeClass : '' }}">
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
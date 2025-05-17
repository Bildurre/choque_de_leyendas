@php
  // Obtenemos todas las páginas raíz publicadas y visibles en el menú, ordenadas por orden
  $rootPages = \App\Models\Page::root()
    ->published()
    ->where('show_in_nav', true)
    ->orderBy('order')
    ->get();
@endphp

@if($rootPages->isNotEmpty())
  @foreach($rootPages as $page)
    @php
      // Verificamos si hay hijas publicadas y visibles en el menú
      $publishedChildren = $page->children()
        ->published()
        ->where('show_in_nav', true)
        ->count();
      $hasPublishedChildren = $publishedChildren > 0;
    @endphp
    
    <li class="nav-item {{ $hasPublishedChildren ? 'nav-item--has-children' : '' }}">
      <a href="{{ localized_route('content.page', $page) }}" 
         class="nav-link {{ request()->url() == localized_route('content.page', $page) ? $activeClass : '' }}">
        {{ $page->title }}
        @if($hasPublishedChildren)
          <x-icon name="chevron-down" size="sm" class="nav-link__icon" />
        @endif
      </a>
      
      @if($hasPublishedChildren)
        <div class="nav-dropdown">
          <ul class="nav-dropdown__list">
            @foreach($page->children()
              ->published()
              ->where('show_in_nav', true)
              ->orderBy('order')
              ->get() as $childPage)
              <li class="nav-dropdown__item">
                <a href="{{ localized_route('content.page', $childPage) }}" 
                   class="nav-dropdown__link {{ request()->url() == localized_route('content.page', $childPage) ? $activeClass : '' }}">
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
@php
  $collectionCount = app(\App\Services\Pdf\TemporaryCollectionService::class)->getTotalCardsCount();
@endphp

<header class="public-header">
  <div class="header-container">
    <div class="header-main-container">
      <div class="header-main">
        <button class="mobile-menu-toggle" aria-label="{{ __('public.menu.toggle') }}" aria-expanded="false">
          <span class="mobile-menu-toggle__icon">
            <span></span>
            <span></span>
            <span></span>
          </span>
        </button>
        
        <a href="{{ route('welcome') }}" class="header-logo logo-link">
          <x-logo />
        </a>
        
        <div class="header-actions">
          <x-language-selector />

          <a href="{{ route('public.pdf-collection.index') }}" 
            class="collection-icon"
            title="{{ __('public.menu.downloads') }}">
            <x-icon name="layers" />
            <span class="collection-counter" 
                  data-collection-count="{{ $collectionCount }}"
                  style="{{ $collectionCount > 0 ? '' : 'display: none;' }}">
              {{ $collectionCount }}
            </span>
          </a>
          
          <x-theme-switcher />
        </div>
      </div>
    </div>
    
    <nav class="header-nav" role="navigation">
      <div class="header-nav__inner">
        {{-- Language selector for mobile only --}}
        <div class="mobile-language-selector">
          <x-language-selector />
        </div>
        
        <ul class="nav-list">
          @auth
            @if(Auth::user()->isAdmin())
              <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link nav-link--admin">
                  {{ __('public.menu.admin_panel') }}
                </a>
              </li>
            @endif
          @endauth
          
          <li class="nav-item">
            <a href="{{ route('public.factions.index') }}" class="nav-link {{ request()->routeIs('public.factions.*') ? 'nav-link--active' : '' }}">
              {{ __('public.menu.factions') }}
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('public.heroes.index') }}" class="nav-link {{ request()->routeIs('public.heroes.*') ? 'nav-link--active' : '' }}">
              {{ __('public.menu.heroes') }}
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('public.cards.index') }}" class="nav-link {{ request()->routeIs('public.cards.*') ? 'nav-link--active' : '' }}">
              {{ __('public.menu.cards') }}
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('public.faction-decks.index') }}" class="nav-link {{ request()->routeIs('public.faction-decks.*') ? 'nav-link--active' : '' }}">
              {{ __('public.menu.faction_decks') }}
            </a>
          </li>
          
          <x-public.page-menu />


          <li class="nav-item">
            <a href="{{ route('public.pdf-collection.index') }}" class="nav-link {{ request()->routeIs('public.pdf-collection.*') ? 'nav-link--active' : '' }}">
              {{ __('public.menu.downloads') }}
            </a>
          </li>
        </ul>
      </div>
    </nav>
  </div>
</header>
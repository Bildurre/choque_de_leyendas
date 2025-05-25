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
              <li class="nav-item nav-item--admin">
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
          
          <x-public.page-menu />
        </ul>
      </div>
    </nav>
  </div>
</header>
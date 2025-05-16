<header class="public-header">
  <div class="header-container">
    <div class="header-main-container">
      <div class="header-main">
        <a href="{{ route('welcome') }}" class="header-logo logo-link">
          <x-logo />
        </a>
        
        <div class="header-actions">
          <x-language-selector />
          <x-theme-switcher />
        </div>
      </div>
    </div>
    
    <div class="header-bottom-container">
      <div class="header-bottom">
        <nav class="header-nav">
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
            
            <x-public.page-menu />

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
          </ul>
        </nav>
      </div>
    </div>
  </div>
</header>
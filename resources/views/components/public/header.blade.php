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
            <li class="nav-item">
              <a href="{{ route('content.index') }}" class="nav-link">{{ __('public.menu.explore') }}</a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">{{ __('public.menu.rules') }}</a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">{{ __('public.menu.gallery') }}</a>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </div>
</header>
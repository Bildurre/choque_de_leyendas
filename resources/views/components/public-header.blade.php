<header x-data="{ 
  showHeader: true, 
  lastScrollY: 0,
  handleScroll() {
    const currentScrollY = window.scrollY;
    this.showHeader = (currentScrollY <= 0) || (currentScrollY < this.lastScrollY);
    this.lastScrollY = currentScrollY;
  }
}" 
@scroll.window="handleScroll()"
:class="{ 'header--hidden': !showHeader }" 
class="public-header">
  <div class="header-container">
    <div class="header-logo">
      <a href="{{ route('welcome') }}" class="logo-link">LOGO</a>
    </div>
    
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
    
    <div class="header-actions">
      <x-language-selector />
    </div>
  </div>
</header>
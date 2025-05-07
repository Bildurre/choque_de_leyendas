<header class="admin-header">
  <div class="admin-header__container">
    <div class="admin-header__top-container">
      <div class="admin-header__top">
        <div class="admin-header__left">
          <button class="sidebar-toggle" id="sidebar-toggle" aria-label="{{ __('admin.toggle_sidebar') }}">
            <span class="sidebar-toggle__bar"></span>
            <span class="sidebar-toggle__bar"></span>
            <span class="sidebar-toggle__bar"></span>
          </button>
          
          <a href="{{ route('admin.dashboard') }}" class="admin-header__logo">
            <x-logo />
          </a>
        </div>

        <div class="admin-header__right">
          <x-theme-switcher />
        </div>
      </div>
    </div>
    
    <div class="admin-header__bottom-container">
      <div class="admin-header__bottom">
        <!-- Esta sección queda vacía por ahora -->
      </div>
    </div>
  </div>
</header>
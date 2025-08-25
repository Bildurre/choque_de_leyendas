<header class="admin-header">
  <div class="admin-header__container">
    <div class="admin-header__top-container">
      <div class="admin-header__top">
        <div class="admin-header__left">
          <x-sidebar-toggle />
          
          <a href="{{ route('admin.dashboard') }}" class="admin-header__logo">
            <x-logo />
          </a>
        </div>

        <div class="admin-header__right">
          <x-language-selector :changeLocaleOnly="true"/>
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
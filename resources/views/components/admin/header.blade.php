<header class="admin-header">
  <div class="admin-header__container">
    <div class="admin-header__left">
      <button class="sidebar-toggle" id="sidebar-toggle" aria-label="{{ __('admin.toggle_sidebar') }}">
        <span class="sidebar-toggle__bar"></span>
        <span class="sidebar-toggle__bar"></span>
        <span class="sidebar-toggle__bar"></span>
      </button>
      
      <a href="{{ route('admin.dashboard') }}" class="admin-header__logo">
        <x-logo-icon />
        <span class="admin-header__title">{{ config('app.name') }}</span>
      </a>
    </div>

    <div class="admin-header__right">
      <x-theme-switcher />
      <x-language-selector />
    </div>
  </div>
</header>
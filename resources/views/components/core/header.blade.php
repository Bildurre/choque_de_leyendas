<header class="admin-header">
  <div class="header-left">

    <button 
      class="sidebar-toggle"
      x-on:click="sidebarOpen = !sidebarOpen"
      aria-label="Toggle sidebar">
      <span></span>
      <span></span>
      <span></span>
    </button>

    <div class="header-logo">
      <a href="{{ route('admin.dashboard') }}" class="logo-container">
        <x-core.application-logo />
      </a>
    </div>
  </div>
    
  <div class="header-title">
    <h1>{{ $title ?? 'Dashboard' }}</h1>
  </div>

  <div class="header-right">
    <x-core.language-selector variant="buttons" />
  </div>
</header>
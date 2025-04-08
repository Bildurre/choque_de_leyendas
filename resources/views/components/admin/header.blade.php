<header class="admin-header">
  <div class="header-left">
    <!-- Sidebar Toggle Button -->
    <button 
      class="sidebar-toggle"
      x-on:click="sidebarOpen = !sidebarOpen"
      aria-label="Toggle sidebar">
      <span></span>
      <span></span>
      <span></span>
    </button>
    
    <!-- Logo en el header -->
    <div class="header-logo">
      <a href="{{ route('admin.dashboard') }}" class="logo-container">
        <x-widgets.game-dice variant="tricolor" size="bg"/>
        <span class="logo-text">ALANDA</span>
      </a>
      <span class="logo-subtitle">CHOQUE DE LEYENDAS</span>
    </div>
    
    <div class="header-title">
      <h1>{{ $title ?? 'Dashboard' }}</h1>
    </div>
  </div>
  
  <div class="header-actions">
    {{ $actions ?? '' }}
  </div>
</header>
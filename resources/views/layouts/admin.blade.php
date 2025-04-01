<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Alanda') }} - @yield('title', 'Panel de Administración')</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Almendra:ital,wght@0,400;0,700;1,400;1,700&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

  <!-- Scripts -->
  @vite(['resources/scss/app.scss', 'resources/js/app.js'])
  
  @stack('styles')
</head>
<body class="admin-body" x-data="{ sidebarOpen: window.innerWidth > 768 }" 
  :class="{ 'sidebar-open': sidebarOpen }">
  <div class="admin-layout">
    <!-- Top Header Bar -->
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
          <div class="logo-container">
            @include('components.game-dice', ['class' => 'logo-dice'])
            <span class="logo-text">ALANDA</span>
          </div>
          <span class="logo-subtitle">CHOQUE DE LEYENDAS</span>
        </div>
        
        <div class="header-title">
          <h1>@yield('header-title', 'Dashboard')</h1>
        </div>
      </div>
      
      <div class="header-actions">
      </div>
    </header>

    <div class="admin-main-container">
      <!-- Sidebar -->
      <aside class="admin-sidebar">
        <!-- Sidebar Navigation Placeholder -->
        <nav class="sidebar-nav">
          <div class="sidebar-section">
            <span class="sidebar-section-title">Componentes</span>
            <ul class="sidebar-section-list">
              <li>
                <a href="{{ route('admin.factions.index') }}"
                   data-route="{{ route('admin.factions.index') }}"
                   class="sidebar-nav-link {{ request()->routeIs('admin.factions.*') ? 'active' : '' }}">
                  <x-game-dice variant="mono-red" size="sm"/>
                  Facciones
                </a>
              </li>
              <!-- More game components will be added here in the future -->
            </ul>
          </div>
        </nav>

        <!-- User Profile Section -->
        <div class="sidebar-footer">
          <div class="user-profile">
            <div class="user-avatar-dice">
              @include('components.game-dice', ['variant' => 'mono', 'color' => '#333333', 'class' => 'user-dice'])
            </div>
            <div class="user-info">
              <span class="user-name">{{ Auth::user()->name }}</span>
              <span class="user-role">Administrador</span>
            </div>
          </div>
          <div class="logout-section">
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="logout-button">
                <span>Cerrar sesión</span>
              </button>
            </form>
          </div>
        </div>
      </aside>

      <!-- Main Content Area -->
      <div class="admin-main">
        <!-- Main Content -->
        <main class="admin-content">
          @yield('content')
        </main>
      </div>
    </div>
  </div>

  @stack('scripts')
</body>
</html>
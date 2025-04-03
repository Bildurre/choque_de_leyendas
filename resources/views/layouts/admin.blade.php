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
          <a href="{{ route('admin.dashboard') }}" class="logo-container">
            @include('components.game-dice', ['class' => 'logo-dice'])
            <span class="logo-text">ALANDA</span>
          </a>
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
      <!-- Sidebar Navigation -->
      <aside class="admin-sidebar">
        <nav class="sidebar-nav">
          <!-- Dashboard link (standalone, before any groups) -->
          <div class="sidebar-dashboard-link">
            <a href="{{ route('admin.dashboard') }}"
              class="sidebar-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
              <x-game-dice variant="mono-blue" size="sm"/>
              Dashboard
            </a>
          </div>
          
          <!-- Componentes Group -->
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
              <li>
                <a href="#"
                  class="sidebar-nav-link {{ request()->routeIs('admin.heroes.*') ? 'active' : '' }}">
                  <x-game-dice variant="mono-green" size="sm"/>
                  Héroes
                </a>
              </li>
              <!-- Otros enlaces de componentes se añadirán aquí -->
            </ul>
          </div>
          
          <!-- Placeholder para futuras secciones -->
          <div class="sidebar-section">
            <span class="sidebar-section-title">Reglas</span>
            <ul class="sidebar-section-list">
              <li><a href="#" class="sidebar-nav-link">Reglas básicas</a></li>
              <li><a href="#" class="sidebar-nav-link">Componentes</a></li>
              <li><a href="#" class="sidebar-nav-link">Anexos</a></li>
            </ul>
          </div>
          
          <div class="sidebar-section">
            <span class="sidebar-section-title">Balance</span>
            <ul class="sidebar-section-list">
              <li><a href="#" class="sidebar-nav-link">Análisis de costes</a></li>
              <li><a href="#" class="sidebar-nav-link">Probabilidades</a></li>
            </ul>
          </div>
          
          <div class="sidebar-section">
            <span class="sidebar-section-title">Exportación</span>
            <ul class="sidebar-section-list">
              <li><a href="#" class="sidebar-nav-link">PDF</a></li>
            </ul>
          </div>
          
          <div class="sidebar-section">
            <span class="sidebar-section-title">Sistema</span>
            <ul class="sidebar-section-list">
              <li><a href="#" class="sidebar-nav-link">Administradores</a></li>
            </ul>
          </div>
        </nav>

        <!-- User Profile Section -->
        <div class="sidebar-footer">
          <!-- ... resto del código ... -->
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
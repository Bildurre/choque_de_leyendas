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
            <x-widgets.game-dice variant="tricolor" size="bg"/>
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
            <x-navigation.sidebar-nav-link 
              :route="route('admin.dashboard')"
              :active="request()->routeIs('admin.dashboard')" 
              icon="mono-blue"
            >
              Dashboard
            </x-navigation.sidebar-nav-link>
          </div>
          
          <x-navigation.sidebar-section title="Componentes">
            <li>
              <x-navigation.sidebar-nav-link 
                :route="route('admin.factions.index')"
                :active="request()->routeIs('admin.factions.*')" 
                icon="mono-red"
              >
                Facciones
              </x-navigation.sidebar-nav-link>
            </li>
            <li>
              <x-navigation.sidebar-nav-link 
                :route="route('admin.hero-abilities.index')"
                :active="request()->routeIs('admin.hero-abilities.*')" 
                icon="mono-green"
              >
                Habilidades
              </x-navigation.sidebar-nav-link>
            </li>
            <li>
              <x-navigation.sidebar-nav-link 
                route="#"
                :active="request()->routeIs('admin.heroes.*')" 
                icon="mono-blue"
              >
                Héroes
              </x-navigation.sidebar-nav-link>
            </li>
            <!-- Otros enlaces de componentes se añadirán aquí -->
          </x-navigation.sidebar-section>

          <!-- Balance Section -->
          <x-navigation.sidebar-section title="Balance">
            <li>
              <x-navigation.sidebar-nav-link 
                :route="route('admin.hero-attributes.edit')"
                :active="request()->routeIs('admin.hero-attributes.*')" 
                icon="red-green"
              >
                Configuración de Atributos
              </x-navigation.sidebar-nav-link>
            </li>
            <li>
              <x-navigation.sidebar-nav-link 
                :route="route('admin.superclasses.index')"
                :active="request()->routeIs('admin.superclasses.*')" 
                icon="green-blue"
              >
                Superclases
              </x-navigation.sidebar-nav-link>
            </li>
            <li>
              <x-navigation.sidebar-nav-link 
                :route="route('admin.hero-classes.index')"
                :active="request()->routeIs('admin.hero-classes.*')" 
                icon="mono-green"
              >
                Clases
              </x-navigation.sidebar-nav-link>
            </li>
          </x-navigation.sidebar-section>

          <!-- Habilidades Section -->
          <x-navigation.sidebar-section title="Habilidades">
            <li>
              <x-navigation.sidebar-nav-link 
                :route="route('admin.attack-types.index')"
                :active="request()->routeIs('admin.attack-types.*')" 
                icon="mono-red"
              >
                Tipos
              </x-navigation.sidebar-nav-link>
            </li>
            <li>
              <x-navigation.sidebar-nav-link 
                :route="route('admin.attack-subtypes.index')"
                :active="request()->routeIs('admin.attack-subtypes.*')" 
                icon="mono-green"
              >
                Subtipos
              </x-navigation.sidebar-nav-link>
            </li>
            <li>
              <x-navigation.sidebar-nav-link 
                :route="route('admin.attack-ranges.index')"
                :active="request()->routeIs('admin.attack-ranges.*')" 
                icon="mono-blue"
              >
                Rangos
              </x-navigation.sidebar-nav-link>
            </li>
          </x-navigation.sidebar-section>
          
          
        </nav>
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
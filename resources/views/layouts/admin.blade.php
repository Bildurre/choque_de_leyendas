@props([
  'title' => '',
  'headerTitle' => '',
  'containerTitle' => '',
  'subtitle' => '',
  'createIcon' => null,
  'createRoute' => null,
  'createLabel' => null,
  'backIcon' => null,
  'backRoute' => null,
  'backLabel' => null
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- <title>{{ config('app.name', 'Alanda') }} - @yield('title', 'Panel de Administración')</title> --}}
  <title>{{ $title ?? 'Panel de Administración'}}</title>

  @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body class="admin-body" x-data="{ sidebarOpen: window.innerWidth > 768 }" 
  :class="{ 'sidebar-open': sidebarOpen }">
  <div class="admin-layout">
    <!-- Top Header Bar -->
    <x-header :title="$headerTitle" />

    <div class="admin-main-container">
      <!-- Sidebar -->
      <x-sidebar />

      <!-- Main Content Area -->
      <div class="admin-main">
        <!-- Main Content -->
        <main class="admin-content">
          <div {{ $attributes->merge(['class' => 'admin-container']) }}>
            @if($title)
              <x-header-actions-bar 
                :title="$title"
                :subtitle="$subtitle"
                :createRoute="$createRoute"
                :createLabel="$createLabel"
                :createIcon="$createIcon"
                :backRoute="$backRoute"
                :backLabel="$backLabel"
                :backIcon="$backIcon"
              />
            @endif
            
            <x-session-alerts />
            
            <div class="page-content">
              {{ $slot }}
            </div>
          </div>
        </main>
      </div>
    </div>
  </div>
</body>
</html>
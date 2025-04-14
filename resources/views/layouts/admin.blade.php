@props([
  'title' => '',
  'headerTitle' => '',
  'containerTitle' => '',
  'subtitle' => ''
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Alanda') }} - @yield('title', 'Panel de Administración')</title>

  @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body class="admin-body" x-data="{ sidebarOpen: window.innerWidth > 768 }" 
  :class="{ 'sidebar-open': sidebarOpen }">
  <div class="admin-layout">
    <!-- Top Header Bar -->
    <x-admin.header :title="$headerTitle" />

    <div class="admin-main-container">
      <!-- Sidebar -->
      <x-admin.sidebar />

      <!-- Main Content Area -->
      <div class="admin-main">
        <!-- Main Content -->
        <main class="admin-content">
          <div {{ $attributes->merge(['class' => 'admin-container']) }}>
            @if($title)
              <x-admin.header-actions-bar 
                :title="$title"
                :subtitle="$subtitle"
                {{ $attributes->only('create_route', 'create_label', 'back_route', 'back_label') }}
              >
                {{ $actions ?? '' }}
              </x-admin.header-actions-bar>
            @endif
            
            <x-session-alerts />
            
            <div class="page-content">
              @yield('page-content')
            </div>
          </div>
        </main>
      </div>
    </div>
  </div>
</body>
</html>
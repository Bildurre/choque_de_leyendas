<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl leading-tight">
          {{ __('Dashboard') }}
      </h2>
  </x-slot>

  <div class="dashboard-container">
      <div class="dashboard-card">
          <h3>Bienvenido al Panel de Administración</h3>
          <p>Esta es una versión preliminar del dashboard.</p>
          
          <div class="dashboard-actions">
              <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="logout-button">
                      {{ __('Cerrar sesión') }}
                  </button>
              </form>
          </div>
      </div>
  </div>
</x-app-layout>

@extends('admin.layouts.page', [
  'title' => 'Dashboard',
  'headerTitle' => 'Dashboard',
  'containerTitle' => 'Admin Dashboard',
  'subtitle' => 'Página de inicio de Administración'
])

@section('page-content')
<div class="dashboard-container">
  <div class="dashboard-row">
    <div class="dashboard-card dashboard-card-lg">
      <h3 class="card-title">Bienvenido al Panel de Administración</h3>
      <p class="card-text">Este es el panel de administración para gestionar el juego "Alanda: Choque de Leyendas". Desde aquí podrás:</p>
      <ul class="card-list">
        <li>Gestionar facciones, héroes y cartas</li>
        <li>Administrar clases, razas y atributos</li>
        <li>Verificar el balance del juego</li>
        <li>Actualizar las reglas y anexos</li>
        <li>Generar exportaciones en PDF</li>
      </ul>
    </div>
  </div>
</div>
@endsection

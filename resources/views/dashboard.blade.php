@extends('layouts.admin')

@section('title', 'Dashboard')

@section('header-title', 'Dashboard')

@section('content')
<div class="dashboard-container">
  <!-- Primera fila: Tarjetas de resumen -->
  <div class="dashboard-row">
    <div class="dashboard-card">
      <div class="card-icon">
        @include('components.game-dice', ['variant' => 'dual', 'size' => 'sm', 'class' => 'card-dice'])
      </div>
      <div class="card-content">
        <h3 class="card-title">Total de Héroes</h3>
        <p class="card-value">87</p>
        <a href="#" class="card-action">+ Crear Nuevo Héroe</a>
      </div>
    </div>
    
    <div class="dashboard-card">
      <div class="card-icon">
        @include('components.game-dice', ['variant' => 'dual', 'size' => 'sm', 'class' => 'card-dice'])
      </div>
      <div class="card-content">
        <h3 class="card-title">Total de Cartas</h3>
        <p class="card-value">245</p>
        <a href="#" class="card-action">+ Crear Nueva Carta</a>
      </div>
    </div>
    
    <div class="dashboard-card">
      <div class="card-icon">
        @include('components.game-dice', ['variant' => 'dual', 'size' => 'sm', 'class' => 'card-dice'])
      </div>
      <div class="card-content">
        <h3 class="card-title">Total de Facciones</h3>
        <p class="card-value">3</p>
        <a href="#" class="card-action">+ Crear Nueva Facción</a>
      </div>
    </div>
  </div>
  
  <!-- Secciones adicionales del dashboard serán implementadas más tarde -->
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

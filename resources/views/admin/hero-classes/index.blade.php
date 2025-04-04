@extends('layouts.admin')

@section('title', 'Clases de Héroes')

@section('header-title', 'Gestión de Clases')

@section('content')
<div class="hero-classes-container">
  <x-header-actions-bar 
    title="Clases de Héroes"
    subtitle="Gestión de clases para la creación de héroes"
    :create_route="route('admin.hero-classes.create')"
    create_label="+ Nueva Clase"
  />

  @if(session('success'))
    <x-alert type="success">
      {{ session('success') }}
    </x-alert>
  @endif

  @if(session('error'))
    <x-alert type="danger">
      {{ session('error') }}
    </x-alert>
  @endif

  <div class="entities-grid">
    @forelse($heroClasses as $heroClass)
      <x-game.hero-class-card 
        :heroClass="$heroClass"
        :editRoute="route('admin.hero-classes.edit', $heroClass)"
        :deleteRoute="route('admin.hero-classes.destroy', $heroClass)"
      />
    @empty
      <x-no-entities 
        message="No hay clases de héroes disponibles"
        :create_route="route('admin.factions.create')"
        create_label="Crear la primera facción"
      />
    @endforelse
  </div>
</div>
@endsection
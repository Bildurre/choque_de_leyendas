@extends('layouts.admin')

@section('title', 'Facciones')

@section('header-title', 'Gestión de Facciones')

@section('content')
<div class="factions-container">
  <x-header-actions-bar 
    title="Facciones"
    subtitle="Gestión de facciones del juego"
    :create_route="route('admin.factions.create')"
    create_label="+ Nueva Facción"
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
    @forelse($factions as $faction)
      <x-game.faction-card 
        :faction="$faction"
        :showRoute="route('admin.factions.show', $faction)"
        :editRoute="route('admin.factions.edit', $faction)"
        :deleteRoute="route('admin.factions.destroy', $faction)"
      />
    @empty
      <x-no-entities 
        message="No hay facciones disponibles"
        :create_route="route('admin.factions.create')"
        create_label="Crear la primera facción"
      />
    @endforelse
  </div>
</div>
@endsection
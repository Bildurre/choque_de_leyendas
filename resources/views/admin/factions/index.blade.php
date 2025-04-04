@extends('layouts.admin')

@section('title', 'Facciones')

@section('header-title', 'Gestión de Facciones')

@section('content')
<x-admin-container
  title="Facciones"
  subtitle="Gestión de facciones del juego"
  :create_route="route('admin.factions.create')"
  create_label="+ Nueva Facción"
>
  <x-entities-grid 
    empty_message="No hay facciones disponibles"
    :create_route="route('admin.factions.create')"
    create_label="Crear la primera facción"
  >
    @foreach($factions as $faction)
      <x-game.faction-card 
        :faction="$faction"
        :showRoute="route('admin.factions.show', $faction)"
        :editRoute="route('admin.factions.edit', $faction)"
        :deleteRoute="route('admin.factions.destroy', $faction)"
      />
    @endforeach
  </x-entities-grid>
</x-admin-container>
@endsection
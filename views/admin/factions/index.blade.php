@extends('admin.layouts.page', [
  'title' => 'Facciones',
  'headerTitle' => 'Gestión de Facciones',
  'containerTitle' => 'Facciones',
  'subtitle' => 'Gestión de facciones del juego',
  'createRoute' => route('admin.factions.create'),
  'createLabel' => '+ Nueva Facción'
])

@section('page-content')
  <x-common.entities-grid 
    empty_message="No hay facciones disponibles"
    :create_route="route('admin.factions.create')"
    create_label="Crear la primera facción"
  >
    @foreach($factions as $faction)
      <x-cards.admin.faction-card 
        :faction="$faction"
        :showRoute="route('admin.factions.show', $faction)"
        :editRoute="route('admin.factions.edit', $faction)"
        :deleteRoute="route('admin.factions.destroy', $faction)"
      />
    @endforeach
  </x-common.entities-grid>
@endsection
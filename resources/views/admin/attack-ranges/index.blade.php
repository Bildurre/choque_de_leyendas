@extends('admin.layouts.page', [
  'title' => 'Rangos de Ataque',
  'headerTitle' => 'Gestión de Rangos',
  'containerTitle' => 'Rangos de Ataque',
  'subtitle' => 'Gestión de rangos para los ataques y habilidades',
  'createRoute' => route('admin.attack-ranges.create'),
  'createLabel' => '+ Nuevo Rango'
])

@section('page-content')
  <x-common.entities-grid 
    empty_message="No hay rangos de ataque disponibles"
    :create_route="route('admin.attack-ranges.create')"
    create_label="Crear el primer rango"
  >
    @foreach($attackRanges as $range)
      <x-cards.admin.attack-range-card 
        :range="$range"
        :editRoute="route('admin.attack-ranges.edit', $range)"
        :deleteRoute="route('admin.attack-ranges.destroy', $range)"
      />
    @endforeach
  </x-common.entities-grid>
@endsection
@extends('admin.layouts.page', [
  'title' => 'Superclases',
  'headerTitle' => 'Gestión de Superclases',
  'containerTitle' => 'Superclases',
  'subtitle' => 'Gestión de superclases para los héroes',
  'createRoute' => route('admin.superclasses.create'),
  'createLabel' => '+ Nueva Superclase'
])

@section('page-content')
  <x-entities-grid 
    empty_message="No hay superclases disponibles"
    :create_route="route('admin.superclasses.create')"
    create_label="Crear la primera superclase"
  >
    @foreach($superclasses as $superclass)
      <x-game.superclass-card 
        :superclass="$superclass"
        :editRoute="route('admin.superclasses.edit', $superclass)"
        :deleteRoute="route('admin.superclasses.destroy', $superclass)"
      />
    @endforeach
  </x-entities-grid>
@endsection
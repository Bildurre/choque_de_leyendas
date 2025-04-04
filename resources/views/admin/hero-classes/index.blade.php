@extends('admin.layouts.page', [
  'title' => 'Clases de Héroes',
  'headerTitle' => 'Gestión de Clases',
  'containerTitle' => 'Facciones',
  'subtitle' => 'Gestión de clases para la creación de héroes',
  'createRoute' => route('admin.hero-classes.create'),
  'createLabel' => '+ Nueva Clase'
])

@section('page-content')
  <x-entities-grid 
    empty_message="No hay clases de héroes disponibles"
    :create_route="route('admin.hero-classes.create')"
    create_label="Crear la primera clase"
    >
    @foreach($heroClasses as $heroClass)
      <x-game.hero-class-card 
        :heroClass="$heroClass"
        :editRoute="route('admin.hero-classes.edit', $heroClass)"
        :deleteRoute="route('admin.hero-classes.destroy', $heroClass)"
      />
    @endforeach
  </x-entities-grid>
@endsection
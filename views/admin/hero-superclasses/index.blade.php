@extends('admin.layouts.page', [
  'title' => 'Superclases',
  'headerTitle' => 'Gestión de Superclases',
  'containerTitle' => 'Superclases',
  'subtitle' => 'Gestión de superclases para los héroes',
  'createRoute' => route('admin.hero-superclasses.create'),
  'createLabel' => '+ Nueva Superclase'
])

@section('page-content')
  <x-common.entities-grid 
    empty_message="No hay superclases disponibles"
    :create_route="route('admin.hero-superclasses.create')"
    create_label="Crear la primera superclase"
  >
    @foreach($heroSuperclasses as $heroSuperclass)
      <x-cards.admin.hero-superclass-card 
        :heroSuperclass="$heroSuperclass"
        :editRoute="route('admin.hero-superclasses.edit', $heroSuperclass)"
        :deleteRoute="route('admin.hero-superclasses.destroy', $heroSuperclass)"
      />
    @endforeach
  </x-common.entities-grid>
@endsection
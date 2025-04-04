@extends('layouts.admin')

@section('title', 'Clases de Héroes')

@section('header-title', 'Gestión de Clases')

@section('content')
<x-admin-container
  title="Clases de Héroes"
  subtitle="Gestión de clases para la creación de héroes"
  :create_route="route('admin.hero-classes.create')"
  create_label="+ Nueva Clase"
>
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
</x-admin-container>
@endsection
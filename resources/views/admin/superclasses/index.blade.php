@extends('layouts.admin')

@section('title', 'Superclases')

@section('header-title', 'Gestión de Superclases')

@section('content')
<x-admin-container
  title="Superclases"
  subtitle="Gestión de superclases para los héroes"
  :create_route="route('admin.superclasses.create')"
  create_label="+ Nueva Superclase"
>
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
</x-admin-container>
@endsection
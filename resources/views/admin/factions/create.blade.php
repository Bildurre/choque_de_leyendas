@extends('admin.layouts.page', [
  'title' => 'Nueva Facción',
  'headerTitle' => 'Crear Facción',
  'containerTitle' => 'Facciones',
  'subtitle' => 'Crea una nueva facción para el juego',
  'backRoute' => route('admin.factions.index')
])

@section('page-content')
  <x-forms.faction-form 
    :submitLabel="'Crear Facción'" 
    :cancelRoute="route('admin.factions.index')" 
  />
@endsection
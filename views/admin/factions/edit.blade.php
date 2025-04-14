@extends('admin.layouts.page', [
  'title' => 'Editar Facción',
  'headerTitle' => 'Editar Facción',
  'containerTitle' => 'Facciones',
  'subtitle' => "Modifica los detalles de la facción $faction->name",
  'createRoute' => route('admin.factions.create'),
  'createLabel' => '+ Nueva Facción',
  'backRoute' => route('admin.factions.index')
])

@section('page-content')
  <x-forms.faction-form 
    :faction="$faction" 
    :submitLabel="'Guardar Cambios'" 
    :cancelRoute="route('admin.factions.index')" 
  />
@endsection
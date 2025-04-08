@extends('admin.layouts.page', [
  'title' => 'Nuevo Tipo de Ataque',
  'headerTitle' => 'Crear Tipo de Ataque',
  'containerTitle' => 'Tipos de Ataque',
  'subtitle' => 'Crea un nuevo tipo principal para categorizar ataques y habilidades',
  'backRoute' => route('admin.attack-types.index')
])

@section('page-content')
  <x-forms.attack-type-form 
    :submitLabel="'Crear Tipo'" 
    :cancelRoute="route('admin.attack-types.index')" 
  />
@endsection
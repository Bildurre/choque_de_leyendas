@extends('admin.layouts.page', [
  'title' => 'Editar Tipo de Ataque',
  'headerTitle' => 'Editar Tipo de Ataque',
  'containerTitle' => 'Tipos de Ataque',
  'subtitle' => "Modifica los detalles del tipo $attackType->name",
  'backRoute' => route('admin.attack-types.index')
])

@section('page-content')
  <x-forms.attack-type-form 
    :attackType="$attackType"
    :submitLabel="'Guardar Cambios'" 
    :cancelRoute="route('admin.attack-types.index')" 
  />
@endsection
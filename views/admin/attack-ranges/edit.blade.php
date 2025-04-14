@extends('admin.layouts.page', [
  'title' => 'Editar Rango de Ataque',
  'headerTitle' => 'Editar Rango de Ataque',
  'containerTitle' => 'Rangos de Ataque',
  'subtitle' => "Modifica los detalles del rango $attackRange->name",
  'backRoute' => route('admin.attack-ranges.index')
])

@section('page-content')
  <x-forms.attack-range-form 
    :attackRange="$attackRange"
    :submitLabel="'Guardar Cambios'" 
    :cancelRoute="route('admin.attack-ranges.index')" 
  />
@endsection
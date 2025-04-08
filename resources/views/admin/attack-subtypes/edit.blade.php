@extends('admin.layouts.page', [
  'title' => 'Editar Subtipo de Ataque',
  'headerTitle' => 'Editar Subtipo de Ataque',
  'containerTitle' => 'Subtipos de Ataque',
  'subtitle' => "Modifica los detalles del subtipo $attackSubtype->name",
  'backRoute' => route('admin.attack-subtypes.index')
])

@section('page-content')
  <x-forms.attack-subtype-form 
    :attackSubtype="$attackSubtype"
    :attackTypes="$attackTypes"
    :submitLabel="'Guardar Cambios'" 
    :cancelRoute="route('admin.attack-subtypes.index')" 
  />
@endsection
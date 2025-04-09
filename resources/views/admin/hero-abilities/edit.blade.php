@extends('admin.layouts.page', [
  'title' => 'Editar Habilidad de Héroe',
  'headerTitle' => 'Editar Habilidad',
  'containerTitle' => 'Habilidades',
  'subtitle' => "Modifica los detalles de la habilidad $heroAbility->name",
  'createRoute' => route('admin.hero-abilities.create'),
  'createLabel' => '+ Nueva Habilidad',
  'backRoute' => route('admin.hero-abilities.index')
])

@section('page-content')
  <x-forms.hero-ability-form 
    :heroAbility="$heroAbility"
    :subtypes="$subtypes"
    :ranges="$ranges"
    :selectedHeroes="$selectedHeroes"
    :isDefault="$isDefault"
    :submitLabel="'Guardar Cambios'" 
    :cancelRoute="route('admin.hero-abilities.index')" 
  />
@endsection
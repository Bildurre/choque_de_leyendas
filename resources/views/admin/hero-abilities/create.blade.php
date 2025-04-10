@extends('admin.layouts.page', [
  'title' => 'Nueva Habilidad de Héroe',
  'headerTitle' => 'Crear Habilidad',
  'containerTitle' => 'Habilidades',
  'subtitle' => 'Crea una nueva habilidad para héroes',
  'createRoute' => route('admin.hero-abilities.create'),
  'createLabel' => '+ Nueva Habilidad',
  'backRoute' => route('admin.hero-abilities.index')
])

@section('page-content')
  <x-forms.hero-ability-form 
    :ranges="$ranges"
    :types="$types"
    :subtypes="$subtypes"
    :selectedHeroes="[]"
    :isDefault="false"
    :submitLabel="'Crear Habilidad'" 
    :cancelRoute="route('admin.hero-abilities.index')" 
  />
@endsection
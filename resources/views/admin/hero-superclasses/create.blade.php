@extends('admin.layouts.page', [
  'title' => 'Nueva Superclase',
  'headerTitle' => 'Crear Superclase',
  'containerTitle' => 'Superclases',
  'subtitle' => 'Crea una nueva superclase',
  'createRoute' => route('admin.hero-superclasses.create'),
  'createLabel' => '+ Nueva Superclase'
])

@section('page-content')
  <x-forms.superclass-form 
    :submitLabel="'Crear Superclase'" 
    :cancelRoute="route('admin.hero-superclasses.index')" 
  />
@endsection
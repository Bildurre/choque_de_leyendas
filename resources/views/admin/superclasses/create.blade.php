@extends('admin.layouts.page', [
  'title' => 'Nueva Superclase',
  'headerTitle' => 'Crear Superclase',
  'containerTitle' => 'Superclases',
  'subtitle' => 'Crea una nueva superclase',
  'createRoute' => route('admin.superclasses.create'),
  'createLabel' => '+ Nueva Superclase'
])

@section('page-content')
  <x-forms.superclass-form 
    :submitLabel="'Crear Superclase'" 
    :cancelRoute="route('admin.superclasses.index')" 
  />
@endsection
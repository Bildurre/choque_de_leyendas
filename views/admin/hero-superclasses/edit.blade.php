@extends('admin.layouts.page', [
  'title' => 'Editar Superclase',
  'headerTitle' => 'Editar Superclase',
  'containerTitle' => 'Superclases',
  'subtitle' => "Modifica los detalles de la superclase $heroSuperclass->name",
  'createRoute' => route('admin.hero-superclasses.create'),
  'createLabel' => '+ Nueva Superclase',
  'backRoute' => route('admin.hero-superclasses.index')
])

@section('page-content')
  <x-forms.hero-superclass-form 
    :heroSuperclass="$heroSuperclass" 
    :submitLabel="'Guardar Cambios'" 
    :cancelRoute="route('admin.hero-superclasses.index')" 
  />
@endsection
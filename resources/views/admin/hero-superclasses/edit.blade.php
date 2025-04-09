@extends('admin.layouts.page', [
  'title' => 'Editar Superclase',
  'headerTitle' => 'Editar Superclase',
  'containerTitle' => 'Superclases',
  'subtitle' => "Modifica los detalles de la superclase $superclass->name",
  'createRoute' => route('admin.hero-superclasses.create'),
  'createLabel' => '+ Nueva Superclase',
  'backRoute' => route('admin.hero-superclasses.index')
])

@section('page-content')
  <x-forms.superclass-form 
    :superclass="$superclass" 
    :submitLabel="'Guardar Cambios'" 
    :cancelRoute="route('admin.hero-superclasses.index')" 
  />
@endsection
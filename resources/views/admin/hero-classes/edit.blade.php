@extends('admin.layouts.page', [
  'title' => 'Editar Clase de Héroe',
  'headerTitle' => 'Editar Clase de Héroe',
  'containerTitle' => 'Clases',
  'subtitle' => "Modifica los detalles de la clase $heroClass->name",
  'createRoute' => route('admin.hero-classes.create'),
  'createLabel' => '+ Nueva Clase',
  'backRoute' => route('admin.hero-classes.index')
])

@section('page-content')
  <x-forms.hero-class-form 
    :heroClass="$heroClass"
    :superclasses="$superclasses"
    :submitLabel="'Guardar Cambios'" 
    :cancelRoute="route('admin.hero-classes.index')" 
  />
@endsection
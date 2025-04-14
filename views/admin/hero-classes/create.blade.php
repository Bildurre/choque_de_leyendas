@extends('admin.layouts.page', [
  'title' => 'Crear Clase',
  'headerTitle' => 'Crear Clase de Héroe',
  'containerTitle' => 'Clases',
  'subtitle' => 'Crea los detalles de una nueva clase',
  'createRoute' => route('admin.hero-classes.create'),
  'createLabel' => '+ Nueva Clase',
  'backRoute' => route("admin.hero-classes.index")
])

@section('page-content')
  <x-forms.hero-class-form 
    :heroSuperclasses="$heroSuperclasses"
    :submitLabel="'Crear Clase'" 
    :cancelRoute="route('admin.hero-classes.index')" 
  />
@endsection
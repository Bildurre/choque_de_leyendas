@extends('admin.layouts.page', [
  'title' => 'Nueva Superclase',
  'headerTitle' => 'Crear Superclase',
  'containerTitle' => 'Superclases',
  'subtitle' => 'Crea una nueva superclase',
  'createRoute' => route('admin.superclasses.create'),
  'createLabel' => '+ Nueva Superclase'
])

@section('page-content')
  <form action="{{ route('admin.superclasses.store') }}" method="POST" class="superclass-form">
    @csrf
    
    <x-form-card 
      submit_label="Crear Superclase"
      :cancel_route="route('admin.superclasses.index')"
    >
      <x-form.field 
        name="name" 
        label="Nombre de la Superclase" 
        :required="true" 
        maxlength="255" 
      />

      <x-form.field 
        name="description" 
        label="Descripción"
        type="textarea" 
        rows="4" 
      />
      
      <x-form.field 
        name="color" 
        label="Color" 
        type="color" 
        value="#3d3df5" 
        :required="true" 
        help="Selecciona un color representativo para esta superclase"
      />
    </x-form-card>
  </form>
@endsection
@extends('admin.layouts.page', [
  'title' => 'Editar Superclase',
  'headerTitle' => 'Editar Superclase',
  'containerTitle' => 'Superclases',
  'subtitle' => 'Modifica los detalles de la superclase {{ $superclass->name }}',
  'createRoute' => route('admin.superclasses.create'),
  'createLabel' => '+ Nueva Superclase',
  'back_route' => 'route("admin.superclasses.index")'
])

@section('page-content')
  <form action="{{ route('admin.superclasses.update', $superclass) }}" method="POST" class="superclass-form">
    @csrf
    @method('PUT')
    
    <x-form-card 
      submit_label="Guardar Cambios"
      :cancel_route="route('admin.superclasses.index')"
    >
      <x-form.field 
        name="name" 
        label="Nombre de la Superclase" 
        :value="$superclass->name ?? ''" 
        :required="true" 
        maxlength="255" 
      />

      <x-form.field 
        name="description" 
        label="Descripción"
        type="textarea"
        :value="$superclass->description ?? ''" 
        rows="4" 
      />
    </x-form-card>
  </form>
@endsection
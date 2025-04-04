// resources/views/admin/superclasses/edit.blade.php
@extends('layouts.admin')

@section('title', 'Editar Superclase')

@section('header-title', 'Editar Superclase')

@section('content')
<div class="superclass-form-container">
  <x-header-actions-bar 
    title="Editar Superclase"
    subtitle="Modifica los detalles de la superclase '{{ $superclass->name }}'"
    :back_route="route('admin.superclasses.index')"
  />

  <form action="{{ route('admin.superclasses.update', $superclass) }}" method="POST" class="superclass-form">
    @csrf
    @method('PUT')
    
    <x-form-card 
      :submit_label="isset($superclass) ? 'Guardar Cambios' : 'Crear Superclase'"
      :cancel_route="route('admin.superclasses.index')"
    >
      <x-form.group name="name" label="Nombre de la Superclase" :required="true">
        <x-form.input 
          name="name" 
          :value="$superclass->name ?? ''" 
          :required="true" 
          maxlength="255" 
        />
      </x-form.group>

      <x-form.group name="description" label="Descripción">
        <x-form.textarea 
          name="description" 
          :value="$superclass->description ?? ''" 
          rows="4" 
        />
      </x-form.group>
    </x-form-card>
  </form>
</div>
@endsection
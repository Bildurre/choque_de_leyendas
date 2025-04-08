// resources/views/admin/attack-types/edit.blade.php
@extends('admin.layouts.page', [
  'title' => 'Editar Tipo de Ataque',
  'headerTitle' => 'Editar Tipo de Ataque',
  'containerTitle' => 'Tipos de Ataque',
  'subtitle' => "Modifica los detalles del tipo $attackType->name",
  'backRoute' => route('admin.attack-types.index')
])

@section('page-content')
  <form action="{{ route('admin.attack-types.update', $attackType) }}" method="POST" class="attack-type-form">
    @csrf
    @method('PUT')
    
    <x-form-card 
      submit_label="Guardar Cambios"
      :cancel_route="route('admin.attack-types.index')"
    >
      <div class="form-section">
        <x-form.field 
          name="name" 
          label="Nombre del Tipo" 
          :value="$attackType->name" 
          :required="true" 
          maxlength="255"
        />

        <x-form.field 
          name="description" 
          label="Descripción" 
          type="textarea" 
          :value="$attackType->description" 
          rows="4" 
        />

        <x-form.field 
          name="color" 
          label="Color" 
          type="color" 
          :value="$attackType->color" 
          :required="true" 
          help="Color representativo para este tipo de ataque"
        />
      </div>
    </x-form-card>
  </form>
@endsection
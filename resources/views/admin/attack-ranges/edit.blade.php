@extends('admin.layouts.page', [
  'title' => 'Editar Rango de Ataque',
  'headerTitle' => 'Editar Rango de Ataque',
  'containerTitle' => 'Rangos de Ataque',
  'subtitle' => "Modifica los detalles del rango $attackRange->name",
  'backRoute' => route('admin.attack-ranges.index')
])

@section('page-content')
  <form action="{{ route('admin.attack-ranges.update', $attackRange) }}" method="POST" enctype="multipart/form-data" class="attack-range-form">
    @csrf
    @method('PUT')
    
    <x-form-card 
      submit_label="Guardar Cambios"
      :cancel_route="route('admin.attack-ranges.index')"
    >
      <div class="form-section">
        <x-form.field 
          name="name" 
          label="Nombre del Rango" 
          :value="$attackRange->name" 
          :required="true" 
          maxlength="255"
        />

        <x-form.field 
          name="description" 
          label="Descripción" 
          type="textarea" 
          :value="$attackRange->description" 
          rows="4" 
        />

        <x-image-uploader
          name="icon" 
          label="Icono" 
          :currentImage="$attackRange->icon"
          acceptFormats="image/jpeg,image/png,image/gif,image/svg+xml"
          help="Sube un icono representativo para este rango de ataque"
        />
      </div>
    </x-form-card>
  </form>
@endsection
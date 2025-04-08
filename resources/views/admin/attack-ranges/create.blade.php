@extends('admin.layouts.page', [
  'title' => 'Nuevo Rango de Ataque',
  'headerTitle' => 'Crear Rango de Ataque',
  'containerTitle' => 'Rangos de Ataque',
  'subtitle' => 'Crea un nuevo rango para los ataques y habilidades',
  'backRoute' => route('admin.attack-ranges.index')
])

@section('page-content')
  <form action="{{ route('admin.attack-ranges.store') }}" method="POST" enctype="multipart/form-data" class="attack-range-form">
    @csrf
    
    <x-form-card 
      submit_label="Crear Rango"
      :cancel_route="route('admin.attack-ranges.index')"
    >
      <div class="form-section">
        <x-form.field 
          name="name" 
          label="Nombre del Rango" 
          :required="true" 
          maxlength="255" 
        />

        <x-form.field 
          name="description" 
          label="Descripción" 
          type="textarea" 
          rows="4" 
        />

        <x-image-uploader
          name="icon" 
          label="Icono" 
          acceptFormats="image/jpeg,image/png,image/gif,image/svg+xml"
          help="Sube un icono representativo para este rango de ataque"
        />
      </div>
    </x-form-card>
  </form>
@endsection
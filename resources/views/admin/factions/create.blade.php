@extends('admin.layouts.page', [
  'title' => 'Nueva Facción',
  'headerTitle' => 'Crear Facción',
  'containerTitle' => 'Facciones',
  'subtitle' => 'Crea una nueva facción para el juego',
  'backRoute' => route('admin.factions.index')
])

@section('page-content')
  <form action="{{ route('admin.factions.store') }}" method="POST" enctype="multipart/form-data" class="faction-form">
    @csrf
    
    <x-form-card 
      submit_label="Crear Facción"
      :cancel_route="route('admin.factions.index')"
    >
      <div class="form-section">
        <x-form.field 
          name="name" 
          label="Nombre de la Facción" 
          :required="true" 
          maxlength="255" 
        />

        <x-form.field 
          name="lore_text" 
          label="Descripción / Lore" 
          type="textarea" 
          rows="5" 
        />

        <x-form.field 
          name="color" 
          label="Color" 
          type="color" 
          value="#3d3df5" 
          :required="true" 
          help="Selecciona un color representativo para la facción"
        />

        <x-image-uploader
          name="icon" 
          label="Icono" 
          acceptFormats="image/jpeg,image/png,image/gif,image/svg+xml"
        />
      </div>
    </x-form-card>
  </form>
@endsection
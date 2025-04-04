@extends('admin.layouts.page', [
  'title' => 'Facciones',
  'headerTitle' => 'Editar Facción',
  'containerTitle' => 'Facciones',
  'subtitle' => "Modifica los detalles de la facción {{ $faction->name }}",
  'createRoute' => route('admin.faction.create'),
  'createLabel' => '+ Nueva Facción'
])

@section('page-content')
  <form action="{{ route('admin.factions.update', $faction) }}" method="POST" enctype="multipart/form-data" class="faction-form">
    @csrf
    @method('PUT')
    
    <x-form-card 
      submit_label="Guardar Cambios"
      :cancel_route="route('admin.factions.index')"
    >
      <div class="form-section">
        <x-form.field 
          name="name" 
          label="Nombre de la Facción" 
          :value="$faction->name" 
          :required="true" 
        />

        <x-form.field 
          name="lore_text" 
          label="Descripción / Lore" 
          type="textarea" 
          :value="$faction->lore_text" 
          rows="5" 
        />

        <x-form.field 
          name="color" 
          label="Color" 
          type="color" 
          :value="$faction->color" 
          :required="true" 
          help="Selecciona un color representativo para la facción"
        />

        <x-image-uploader
          name="icon" 
          label="Icono" 
          :currentImage="$faction->icon"
          acceptFormats="image/jpeg,image/png,image/gif,image/svg+xml"
        />
      </div>
    </x-form-card>
  </form>
@endsection
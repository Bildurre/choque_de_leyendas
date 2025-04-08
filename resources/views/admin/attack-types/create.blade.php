@extends('admin.layouts.page', [
  'title' => 'Nuevo Tipo de Ataque',
  'headerTitle' => 'Crear Tipo de Ataque',
  'containerTitle' => 'Tipos de Ataque',
  'subtitle' => 'Crea un nuevo tipo principal para categorizar ataques y habilidades',
  'backRoute' => route('admin.attack-types.index')
])

@section('page-content')
  <form action="{{ route('admin.attack-types.store') }}" method="POST" class="attack-type-form">
    @csrf
    
    <x-form-card 
      submit_label="Crear Tipo"
      :cancel_route="route('admin.attack-types.index')"
    >
      <div class="form-section">
        <x-form.field 
          name="name" 
          label="Nombre del Tipo" 
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
          help="Color representativo para este tipo de ataque"
        />
      </div>
    </x-form-card>
  </form>
@endsection
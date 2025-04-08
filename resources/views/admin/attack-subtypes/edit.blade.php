@extends('admin.layouts.page', [
  'title' => 'Editar Subtipo de Ataque',
  'headerTitle' => 'Editar Subtipo de Ataque',
  'containerTitle' => 'Subtipos de Ataque',
  'subtitle' => "Modifica los detalles del subtipo $attackSubtype->name",
  'backRoute' => route('admin.attack-subtypes.index')
])

@section('page-content')
  <form action="{{ route('admin.attack-subtypes.update', $attackSubtype) }}" method="POST" class="attack-subtype-form">
    @csrf
    @method('PUT')
    
    <x-form-card 
      submit_label="Guardar Cambios"
      :cancel_route="route('admin.attack-subtypes.index')"
    >
      <div class="form-section">
        <x-form.field 
          name="name" 
          label="Nombre del Subtipo" 
          :value="$attackSubtype->name" 
          :required="true" 
          maxlength="255"
        />

        <x-form.field 
          name="attack_type_id" 
          label="Tipo de Ataque" 
          type="select"
          :value="$attackSubtype->attack_type_id"
          :required="true"
          :options="$attackTypes->pluck('name', 'id')->toArray()"
          placeholder="Selecciona un tipo de ataque"
          help="El tipo principal al que pertenece este subtipo"
        />

        <x-form.field 
          name="description" 
          label="Descripción" 
          type="textarea" 
          :value="$attackSubtype->description" 
          rows="4" 
        />

        <x-form.field 
          name="color" 
          label="Color" 
          type="color" 
          :value="$attackSubtype->color ?? ''" 
          help="Color personalizado para este subtipo. Si se deja vacío, heredará el color del tipo principal."
        />
      </div>
    </x-form-card>
  </form>
@endsection
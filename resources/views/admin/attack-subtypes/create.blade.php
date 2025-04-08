@extends('admin.layouts.page', [
  'title' => 'Nuevo Subtipo de Ataque',
  'headerTitle' => 'Crear Subtipo de Ataque',
  'containerTitle' => 'Subtipos de Ataque',
  'subtitle' => 'Crea un nuevo subtipo para categorizar ataques y habilidades',
  'backRoute' => route('admin.attack-subtypes.index')
])

@section('page-content')
  <form action="{{ route('admin.attack-subtypes.store') }}" method="POST" class="attack-subtype-form">
    @csrf
    
    <x-form-card 
      submit_label="Crear Subtipo"
      :cancel_route="route('admin.attack-subtypes.index')"
    >
      <div class="form-section">
        <x-form.field 
          name="name" 
          label="Nombre del Subtipo" 
          :required="true" 
          maxlength="255" 
        />

        <x-form.field 
          name="attack_type_id" 
          label="Tipo de Ataque" 
          type="select"
          :required="true"
          :options="$attackTypes->pluck('name', 'id')->toArray()"
          placeholder="Selecciona un tipo de ataque"
          help="El tipo principal al que pertenece este subtipo"
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
          help="Color personalizado para este subtipo (opcional). Si se deja vacío, heredará el color del tipo principal."
        />
      </div>
    </x-form-card>
  </form>
@endsection
@extends('admin.layouts.page', [
  'title' => 'Editar Clase de Héroe',
  'headerTitle' => 'Editar Clase de Héroe',
  'containerTitle' => 'Clases',
  'subtitle' => "Modifica los detalles de la clase $heroClass->name",
  'createRoute' => route('admin.hero-classes.create'),
  'createLabel' => '+ Nueva Clase',
  'backRoute' => route('admin.hero-classes.index')
])

@section('page-content')
  <form action="{{ route('admin.hero-classes.update', $heroClass) }}" method="POST" class="hero-class-form">
    @csrf
    @method('PUT')
    
    <x-form-card 
      submit_label="Guardar Cambios"
      :cancel_route="route('admin.hero-classes.index')"
    >
      <div class="form-section">
        <x-form.field 
          name="name" 
          label="Nombre de la Clase" 
          :value="$heroClass->name ?? ''" 
          :required="true"
          maxlength="255"
        />

        <x-form.field 
          name="description" 
          label="Descripción"
          type="textarea"
          :value="$heroClass->description ?? ''"
          rows="3"
        />

        <x-form.field 
          name="superclass_id" 
          label="Superclase" 
          :value="$heroClass->superclass_id ?? ''" 
          :required="true"
          type="select"
          :options="$superclasses->pluck('name', 'id')->toArray()"
        />

        <x-form.field 
          name="passive" 
          label="Habilidad Pasiva" 
          :value="$heroClass->passive ?? ''"
          type="textarea"
          rows="4"
        />

        <div class="attribute-modifiers-section">
          <h3>Modificadores de Atributos</h3>
          <p class="form-text">Ajusta los modificadores de atributos. El total de modificadores debe ser entre -3 y +3.</p>
          
          @php
            $attributes = [
              'agility' => 'Agilidad',
              'mental' => 'Mental',
              'will' => 'Voluntad', 
              'strength' => 'Fuerza',
              'armor' => 'Armadura'
            ];
          @endphp

          <div class="entities-grid">
            @foreach($attributes as $key => $label)
              <x-form.field
                name="{{ $key }}_modifier" 
                label="{{ $label }}" 
                :value="$heroClass->{$key . '_modifier'} ?? 0"
                type="number"
                min="-2" 
                max="2"
                required
              />
            @endforeach
          </div>

          <div class="modifiers-total-section">
            <p>Total de modificadores absolutos: <span id="modifiers-total">{{ 
              abs($heroClass->agility_modifier) +
              abs($heroClass->mental_modifier) +
              abs($heroClass->will_modifier) +
              abs($heroClass->strength_modifier) +
              abs($heroClass->armor_modifier)
            }}</span>/3</p>
            @error('modifiers')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>
    </x-form-card>
  </form>
@endsection
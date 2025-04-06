@extends('admin.layouts.page', [
  'title' => 'Crear Clase',
  'headerTitle' => 'Crear Clase de Héroe',
  'containerTitle' => 'Clases',
  'subtitle' => 'Crea los detalles de una nueva clase',
  'createRoute' => route('admin.hero-classes.create'),
  'createLabel' => '+ Nueva Clase',
  'backRoute' => route("admin.hero-classes.index")
])

@section('page-content')
  <form action="{{ route('admin.hero-classes.store') }}" method="POST" class="hero-class-form">
    @csrf
    
    <x-form-card 
      submit_label="Crear Clase"
      :cancel_route="route('admin.hero-classes.index')"
    >
      <div class="form-section">
        <x-form.field 
          name="name" 
          label="Nombre de la Clase" 
          :required="true"
          maxlength="255"
        />

        <x-form.field 
          name="description" 
          label="Descripción"
          type="textarea"
          rows="3"
        />

        <x-form.field 
          name="superclass_id" 
          label="Superclase" 
          :required="true"
          type="select"
          :options="$superclasses->pluck('name', 'id')->toArray()"
        />

        <x-form.field 
          name="passive" 
          label="Habilidad Pasiva" 
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
                type="number"
                value="0"
                min="-2" 
                max="2"
                required
              />
            @endforeach
          </div>

          <div class="modifiers-total-section">
            <p>Total de modificadores absolutos: <span id="modifiers-total">0</span>/3</p>
            @error('modifiers')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>
    </x-form-card>
  </form>
@endsection
@extends('admin.layouts.page', [
  'title' => 'Editar Clase de Héroe',
  'headerTitle' => 'Editar Clase de Héroe',
  'containerTitle' => 'Clases',
  'subtitle' => "Modifica los detalles de la clase {$heroClass->name}",
  'createRoute' => route('admin.hero-classes.create'),
  'createLabel' => '+ Nueva Clase',
  'backRoute' => route('admin.hero-classes.index')
])

@section('page-content')
  <form action="{{ route('admin.hero-classes.update', $heroClass) }}" method="POST" class="hero-class-form">
    @csrf
    @method('PUT')
    
    <x-form-card submit_label="Editar Clase"
    :cancel_route="route('admin.hero-classes.index')">
      <div class="form-section">
        <x-form.field 
          name="name" 
          label="Nombre de la Clase" 
          :value="$heroClass->name ?? ''" 
          :required="true"
          maxlength="255"
        />

        <x-form.field 
          name="superclass_id" 
          label="Superclase" 
          :value="$heroClass->superclass_id ?? ''" 
          :required="true"
          :type="select"
          :options="$superclasses->pluck('name', 'id')->toArray()"
        />

        <x-form.field 
          name="passive" 
          label="Habilidad Pasiva" 
          :value="$heroClass->passive"
          :type="textarea"
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
                :value="{{ old($key . '_modifier', $heroClass->{$key . '_modifier'}) }}"
                :type="number"
                min="-2" 
                max="2"
                required
              />
            @endforeach
          </div>
        </div>
      </div>
    </x-form-card>
  </form>
@endsection
@extends('admin.layouts.page', [
  'title' => 'Nueva Habilidad de Héroe',
  'headerTitle' => 'Crear Habilidad',
  'containerTitle' => 'Habilidades',
  'subtitle' => 'Crea una nueva habilidad para héroes',
  'createRoute' => route('admin.hero-abilities.create'),
  'createLabel' => '+ Nueva Habilidad',
  'backRoute' => route('admin.hero-abilities.index')
])

@section('page-content')
  <form action="{{ route('admin.hero-abilities.store') }}" method="POST" class="hero-ability-form">
    @csrf
    
    <x-form-card 
      submit_label="Crear Habilidad"
      :cancel_route="route('admin.hero-abilities.index')"
    >
      <div class="form-section">
        <h3>Información Básica</h3>
        
        <x-form.field 
          name="name" 
          label="Nombre de la Habilidad" 
          :required="true" 
          maxlength="255" 
        />
        
        <div class="form-row">
          <x-form.field 
            name="is_passive" 
            label="Habilidad Pasiva" 
            type="checkbox" 
            help="Marcar si la habilidad es pasiva (sin costo de activación)"
          />
        </div>

        <div class="cost-section" id="cost-section">
          <x-form.cost-input 
            name="cost" 
            label="Coste de Activación" 
            placeholder="Ej: RRG (Rojo, Rojo, Verde)"
          />
        </div>
        
        <h3>Tipo y Rango</h3>
        
        <div class="form-row">
          <div class="form-column">
            <x-form.field 
              name="attack_type_id" 
              label="Tipo de Habilidad"
              type="select"
              placeholder="Selecciona un tipo"
              :options="$subtypes->map(function($subtype) { return $subtype->type; })->unique('id')->pluck('name', 'id')->toArray()"
            />
          </div>
          
          <div class="form-column">
            <x-form.field 
              name="attack_subtype_id" 
              label="Subtipo" 
              type="select"
              placeholder="Selecciona un subtipo"
              :options="[]"
            />
          </div>
        </div>
        
        <div class="form-row">
          <x-form.field 
            name="attack_range_id" 
            label="Rango" 
            type="select"
            placeholder="Selecciona un rango"
            :options="$ranges->pluck('name', 'id')->toArray()"
          />
        </div>
        
        <h3>Descripción</h3>
        
        <x-form.wysiwyg-editor 
          name="description" 
          :required="true" 
          :imageList="[
            ['title' => 'Dado Rojo', 'value' => asset('images/dice-red.svg')],
            ['title' => 'Dado Verde', 'value' => asset('images/dice-green.svg')],
            ['title' => 'Dado Azul', 'value' => asset('images/dice-blue.svg')]
          ]"
        />
        
        <h3>Asignar a Héroes</h3>
        
        <div class="form-row">
          <x-form.field 
            name="is_default" 
            label="Habilidad por Defecto" 
            type="checkbox" 
            help="Marcar si la habilidad viene por defecto con los héroes seleccionados"
          />
        </div>
        
        <div class="heroes-selection">
          <label class="form-label">Héroes que tienen esta habilidad</label>
          <div class="heroes-grid">
            @foreach($heroes as $hero)
              <div class="hero-checkbox">
                <input type="checkbox" name="hero_ids[]" id="hero_{{ $hero->id }}" value="{{ $hero->id }}">
                <label for="hero_{{ $hero->id }}">{{ $hero->name }}</label>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </x-form-card>
  </form>
@endsection

@extends('admin.layouts.page', [
  'title' => 'Editar Habilidad de Héroe',
  'headerTitle' => 'Editar Habilidad',
  'containerTitle' => 'Habilidades',
  'subtitle' => "Modifica los detalles de la habilidad $heroAbility->name",
  'createRoute' => route('admin.hero-abilities.create'),
  'createLabel' => '+ Nueva Habilidad',
  'backRoute' => route('admin.hero-abilities.index')
])

@section('page-content')
  <form action="{{ route('admin.hero-abilities.update', $heroAbility) }}" method="POST" class="hero-ability-form">
    @csrf
    @method('PUT')
    
    <input type="hidden" id="current-subtype-id" value="{{ $heroAbility->attack_subtype_id }}">
    
    <x-form-card 
      submit_label="Guardar Cambios"
      :cancel_route="route('admin.hero-abilities.index')"
    >
      <div class="form-section">
        <h3>Información Básica</h3>
        
        <x-form.field 
          name="name" 
          label="Nombre de la Habilidad" 
          :value="$heroAbility->name" 
          :required="true" 
          maxlength="255" 
        />
        
        <div class="form-row">
          <x-form.field 
            name="is_passive" 
            label="Habilidad Pasiva" 
            type="checkbox" 
            :checked="$heroAbility->is_passive"
            help="Marcar si la habilidad es pasiva (sin costo de activación)"
          />
        </div>

        <div class="cost-section" id="cost-section">
          <x-form.cost-input 
            name="cost" 
            label="Coste de Activación" 
            :value="$heroAbility->cost"
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
              :value="$heroAbility->subtype ? $heroAbility->subtype->type->id : ''"
              :options="$subtypes->map(function($subtype) { return $subtype->type; })->unique('id')->pluck('name', 'id')->toArray()"
            />
          </div>
          
          <div class="form-column">
            <x-form.field 
              name="attack_subtype_id" 
              label="Subtipo" 
              type="select"
              placeholder="Selecciona un subtipo"
              :value="$heroAbility->attack_subtype_id"
              :options="$heroAbility->subtype ? $subtypes->where('attack_type_id', $heroAbility->subtype->type->id)->pluck('name', 'id')->toArray() : []"
            />
          </div>
        </div>
        
        <div class="form-row">
          <x-form.field 
            name="attack_range_id" 
            label="Rango" 
            type="select"
            placeholder="Selecciona un rango"
            :value="$heroAbility->attack_range_id"
            :options="$ranges->pluck('name', 'id')->toArray()"
          />
        </div>
        
        <h3>Descripción</h3>
        
        <x-form.wysiwyg-editor 
          name="description" 
          :value="$heroAbility->description"
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
            :checked="$isDefault"
            help="Marcar si la habilidad viene por defecto con los héroes seleccionados"
          />
        </div>
        
        <div class="heroes-selection">
          <label class="form-label">Héroes que tienen esta habilidad</label>
          <div class="heroes-grid">
            @foreach($heroes as $hero)
              <div class="hero-checkbox">
                <input 
                  type="checkbox" 
                  name="hero_ids[]" 
                  id="hero_{{ $hero->id }}" 
                  value="{{ $hero->id }}"
                  {{ in_array($hero->id, $selectedHeroes) ? 'checked' : '' }}
                >
                <label for="hero_{{ $hero->id }}">{{ $hero->name }}</label>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </x-form-card>
  </form>
@endsection
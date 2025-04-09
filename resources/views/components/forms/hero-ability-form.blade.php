@props([
  'heroAbility' => null, 
  'subtypes' => [], 
  'ranges' => [], 
  'selectedHeroes' => [], 
  'submitLabel' => 'Guardar', 
  'cancelRoute' => null
])

<form 
  action="{{ $heroAbility ? route('admin.hero-abilities.update', $heroAbility) : route('admin.hero-abilities.store') }}" 
  method="POST" 
  class="hero-ability-form"
>
  @csrf
  @if($heroAbility) @method('PUT') @endif
  
  @if($heroAbility)
    <input type="hidden" id="current-subtype-id" value="{{ $heroAbility->attack_subtype_id }}">
  @endif
  
  <x-form-card 
    :submit_label="$submitLabel"
    :cancel_route="$cancelRoute"
  >
    <div class="form-section">
      <h3>Información Básica</h3>
      
      <x-form.field 
        name="name" 
        label="Nombre de la Habilidad" 
        :value="$heroAbility->name ?? ''" 
        :required="true" 
        maxlength="255" 
      />
      
      <div class="cost-section">
        <x-form.cost-input 
          name="cost" 
          label="Coste de Activación" 
          :value="$heroAbility->cost ?? ''"
          placeholder="Ej: RRG (Rojo, Rojo, Verde)"
          required
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
            :value="$heroAbility->subtype->type->id ?? ''"
            :options="$subtypes->map(function($subtype) { return $subtype->type; })->unique('id')->pluck('name', 'id')->toArray()"
          />
        </div>
        
        <div class="form-column">
          <x-form.field 
            name="attack_subtype_id" 
            label="Subtipo" 
            type="select"
            placeholder="Selecciona un subtipo"
            :value="$heroAbility->attack_subtype_id ?? ''"
            :options="$heroAbility && $heroAbility->subtype ? $subtypes->where('attack_type_id', $heroAbility->subtype->type->id)->pluck('name', 'id')->toArray() : []"
          />
        </div>
      </div>
      
      <div class="form-row">
        <x-form.field 
          name="attack_range_id" 
          label="Rango" 
          type="select"
          placeholder="Selecciona un rango"
          :value="$heroAbility->attack_range_id ?? ''"
          :options="$ranges->pluck('name', 'id')->toArray()"
        />
      </div>
      
      <h3>Descripción</h3>
      
      <x-form.wysiwyg-editor 
        name="description" 
        :value="$heroAbility->description ?? ''"
        :required="true" 
        :imageList="[
          ['title' => 'Dado Rojo', 'value' => asset('images/dice-red.svg')],
          ['title' => 'Dado Verde', 'value' => asset('images/dice-green.svg')],
          ['title' => 'Dado Azul', 'value' => asset('images/dice-blue.svg')]
        ]"
      />
    </div>
  </x-form-card>
</form>
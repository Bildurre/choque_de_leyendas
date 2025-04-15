@props([
  'heroAbility' => null, 
  'ranges' => [], 
  'types' => [],
  'subtypes' => [], 
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
  
  <x-form.card 
    :submit_label="$submitLabel"
    :cancel_route="$cancelRoute"
  >
    <div class="form-section">

      <x-form.field 
        name="name" 
        label="Nombre de la Habilidad" 
        :value="$heroAbility->name ?? ''" 
        :required="true" 
        maxlength="255" 
      />
      
      <x-form.cost-input 
        name="cost" 
        label="Coste de Activación" 
        :value="$heroAbility->cost ?? ''"
        placeholder="Ej: RRG (Rojo, Rojo, Verde)"
        required
      />
      <div class="form-row">
        <x-form.field 
          name="attack_range_id" 
          label="Rango" 
          type="select"
          placeholder="Selecciona un rango"
          :value="$heroAbility->attack_range_id ?? ''"
          :options="$ranges->pluck('name', 'id')->toArray()"
        />

        <x-form.field 
          name="attack_type_id" 
          label="Tipo" 
          type="select"
          placeholder="Selecciona un tipo"
          :value="$heroAbility->attack_type_id ?? ''"
          :options="$types->pluck('name', 'id')->toArray()"
        />

        <x-form.field 
          name="attack_subtype_id" 
          label="Subtipo" 
          type="select"
          placeholder="Selecciona un subtipo"
          :value="$heroAbility->attack_subtype_id ?? ''"
          :options="$subtypes->pluck('name', 'id')->toArray()"
        />
      </div>
      
      <x-form.wysiwyg-editor 
        name="description"
        label="Descripción"
        :value="$heroAbility->description ?? ''"
        :required="true" 
      />
    </div>
  </x-form.card>
</form>
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
    <x-form.field 
      name="name" 
      label="Nombre de la Habilidad" 
      :value="$heroAbility->name ?? ''" 
      required
      max="255" 
    />
    
    <x-form.cost-input 
      name="cost" 
      label="Coste de Activación" 
      :value="$heroAbility->cost ?? ''"
      required
    />

    <div class="form-row">
      <x-form.select
        name="attack_range_id" 
        label="Rango" 
        placeholder="Selecciona un rango"
        :value="$heroAbility->attack_range_id ?? ''"
        :options="$ranges->pluck('name', 'id')->toArray()"
        required
      />

      <x-form.select
        name="attack_subtype_id" 
        label="Subtipo" 
        placeholder="Selecciona un subtipo"
        :value="$heroAbility->attack_subtype_id ?? ''"
        :options="$subtypes->pluck('name', 'id')->toArray()"
        required
      />
    </div>
    
    <x-form.checkbox
      name="blast" 
      label="Área"
    />
    
    <x-form.wysiwyg
      name="description"
      label="Descripción"
      :value="$heroAbility->description ?? ''"
      required
    />
  </x-form.card>
</form>
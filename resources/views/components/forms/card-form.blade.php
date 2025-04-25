@props(['card' => null, 'factions' => [], 'cardTypes' => [], 'equipmentTypes' => [], 'attackRanges' => [], 'attackSubtypes' => [], 'heroAbilities' => [], 'submitLabel' => 'Guardar', 'cancelRoute' => null])

<form 
  action="{{ $card ? route('admin.cards.update', $card) : route('admin.cards.store') }}" 
  method="POST" 
  enctype="multipart/form-data" 
  class="card-form"
  id="card-form"
>
  @csrf
  @if($card) @method('PUT') @endif
  
  <x-form.card 
    :submit_label="$submitLabel"
    :cancel_route="$cancelRoute"
  >
    <div class="form-row">
      <x-form.field 
        name="name" 
        label="Nombre de la Carta" 
        :value="$card->name ?? ''" 
        required
        maxlength="255" 
      />
      
      <x-form.select
        name="faction_id" 
        label="Facción" 
        placeholder="Selecciona una facción (opcional)"
        :value="$card->faction_id ?? ''"
        :options="$factions->pluck('name', 'id')->toArray()"
      />
    </div>
    
    <div class="form-row">
      <x-form.select
        name="card_type_id" 
        label="Tipo de Carta" 
        placeholder="Selecciona un tipo de carta"
        :value="$card->card_type_id ?? ''"
        :options="$cardTypes->pluck('name', 'id')->toArray()"
        required
      />
      
      <x-form.select
        name="equipment_type_id" 
        label="Tipo de Equipo" 
        placeholder="Selecciona un tipo de equipo (opcional)"
        :value="$card->equipment_type_id ?? ''"
        :options="$equipmentTypes->pluck('name', 'id')->toArray()"
        id="equipment_type_id"
      />
      
      <x-form.field 
        name="hands" 
        label="Manos" 
        type="number"
        :value="$card->hands ?? ''" 
        min="1"
        max="2"
        id="hands_field"
        class="hands-field"
      />
    </div>
    
    <div class="form-row">
      <x-form.select
        name="attack_range_id" 
        label="Rango de Ataque" 
        placeholder="Selecciona un rango (opcional)"
        :value="$card->attack_range_id ?? ''"
        :options="$attackRanges->pluck('name', 'id')->toArray()"
      />
      
      <x-form.select
        name="attack_subtype_id" 
        label="Subtipo de Ataque" 
        placeholder="Selecciona un subtipo (opcional)"
        :value="$card->attack_subtype_id ?? ''"
        :options="$attackSubtypes->pluck('name', 'id')->toArray()"
      />
      
      <x-form.checkbox
        name="blast" 
        label="Área"
        :checked="$card->blast ?? false"
      />
    </div>
    
    <div class="form-row">
      <x-form.cost-input 
        name="cost" 
        label="Coste" 
        :value="$card->cost ?? ''"
      />
      
      <x-form.select
        name="hero_ability_id" 
        label="Habilidad de Héroe" 
        placeholder="Selecciona una habilidad (opcional)"
        :value="$card->hero_ability_id ?? ''"
        :options="$heroAbilities->pluck('name', 'id')->toArray()"
      />
    </div>
    
    <x-form.wysiwyg
      name="effect" 
      label="Efecto" 
      :value="$card->effect ?? ''"
      rows="5"
      advanced="true"
    />
    
    <x-form.wysiwyg
      name="restriction" 
      label="Restricción" 
      :value="$card->restriction ?? ''"
      rows="3"
    />
    
    <x-form.image-uploader
      name="image" 
      label="Imagen de la Carta" 
      :currentImage="$card->image ?? null"
      help="Formatos: JPEG, PNG, JPG, GIF, SVG. Tamaño máximo: 2MB."
    />
  </x-form.card>
</form>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const equipmentTypeSelect = document.getElementById('equipment_type_id');
    const handsField = document.getElementById('hands_field');
    
    // Initial state
    updateHandsVisibility();
    
    // Add event listener for equipment type change
    if (equipmentTypeSelect) {
      equipmentTypeSelect.addEventListener('change', updateHandsVisibility);
    }
    
    function updateHandsVisibility() {
      // Get all weapon equipment types
      const weaponTypes = {!! json_encode($equipmentTypes->where('category', 'weapon')->pluck('id')->toArray()) !!};
      
      if (equipmentTypeSelect.value && weaponTypes.includes(parseInt(equipmentTypeSelect.value))) {
        handsField.parentElement.style.display = 'block';
        handsField.required = true;
      } else {
        handsField.parentElement.style.display = 'none';
        handsField.required = false;
        handsField.value = '';
      }
    }
  });
</script>
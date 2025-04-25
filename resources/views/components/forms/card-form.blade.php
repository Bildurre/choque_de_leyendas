@props(['card' => null, 'factions' => [], 'cardTypes' => [], 'equipmentTypes' => [], 'attackRanges' => [], 'attackSubtypes' => [], 'heroAbilities' => [], 'submitLabel' => 'Guardar', 'cancelRoute' => null])

<form 
  action="{{ $card ? route('admin.cards.update', $card) : route('admin.cards.store') }}" 
  method="POST" 
  enctype="multipart/form-data" 
  class="card-form"
  id="card-form"
  data-weapon-types="{{ htmlspecialchars(json_encode($equipmentTypes->where('category', 'weapon')->pluck('id')->toArray()), ENT_QUOTES, 'UTF-8') }}"
  data-equipment-types="{{ htmlspecialchars(json_encode($cardTypes->where('name', 'Equipo')->pluck('id')->toArray()), ENT_QUOTES, 'UTF-8') }}"
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
      
      <x-form.cost-input 
        name="cost" 
        label="Coste" 
        :value="$card->cost ?? ''"
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
        placeholder="Selecciona un tipo de equipo"
        :value="$card->equipment_type_id ?? ''"
        :options="$equipmentTypes->pluck('name', 'id')->toArray()"
        :hiddenCondition="!(isset($card) && $card->isEquipment())"
      />
      
      <x-form.field 
        name="hands" 
        label="Manos" 
        type="number"
        :value="$card->hands ?? ''" 
        min="1"
        max="2"
        class="hands-field"
        :hiddenCondition="!(isset($card) && $card->isWeapon())"
      />
    </div>
    
    <div class="form-row">
      <x-form.checkbox
        name="is_attack" 
        label="Es un Ataque"
        :checked="$card->is_attack ?? false"
      />
      
      <x-form.checkbox
        name="has_hero_ability" 
        label="Añade Habilidad de Héroe"
        :checked="$card->has_hero_ability ?? false"
      />
    </div>
    
    <div class="form-row attack-fields">
      <x-form.select
        name="attack_range_id" 
        label="Rango de Ataque" 
        placeholder="Selecciona un rango"
        :value="$card->attack_range_id ?? ''"
        :options="$attackRanges->pluck('name', 'id')->toArray()"
        :hiddenCondition="!(isset($card) && $card->is_attack)"
      />
      
      <x-form.select
        name="attack_subtype_id" 
        label="Subtipo de Ataque" 
        placeholder="Selecciona un subtipo"
        :value="$card->attack_subtype_id ?? ''"
        :options="$attackSubtypes->pluck('name', 'id')->toArray()"
        :hiddenCondition="!(isset($card) && $card->is_attack)"
      />
      
      <x-form.checkbox
        name="blast" 
        label="Área"
        :checked="$card->blast ?? false"
        :hiddenCondition="!(isset($card) && $card->is_attack)"
      />
    </div>
    
    <x-form.select
      name="hero_ability_id" 
      label="Habilidad de Héroe" 
      placeholder="Selecciona una habilidad"
      :value="$card->hero_ability_id ?? ''"
      :options="$heroAbilities->pluck('name', 'id')->toArray()"
      :hiddenCondition="!(isset($card) && $card->has_hero_ability)"
    />
    
    <x-form.textarea
      name="lore_text" 
      label="Trasfondo" 
      :value="$card->lore_text ?? ''"
    />
    
    <x-form.wysiwyg
      name="effect" 
      label="Efecto" 
      :value="$card->effect ?? ''"
      advanced="true"
    />
    
    <x-form.wysiwyg
      name="restriction" 
      label="Restricción" 
      :value="$card->restriction ?? ''"
    />
    
    <x-form.image-uploader
      name="image" 
      label="Imagen de la Carta" 
      :currentImage="$card->image ?? null"
      help="Formatos: JPEG, PNG, JPG, GIF, SVG. Tamaño máximo: 2MB."
    />
  </x-form.card>
</form>
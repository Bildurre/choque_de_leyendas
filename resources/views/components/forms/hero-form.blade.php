@props([
  'hero' => null, 
  'factions' => [], 
  'heroRaces' => [],
  'heroClasses' => [], 
  'attributesConfig' => null,
  'submitLabel' => 'Guardar', 
  'cancelRoute' => null
])

<form 
  action="{{ $hero ? route('admin.heroes.update', $hero) : route('admin.heroes.store') }}" 
  method="POST" 
  enctype="multipart/form-data" 
  class="hero-form"
  id="hero-form"
  data-min-value="{{ $attributesConfig->min_attribute_value }}"
  data-max-value="{{ $attributesConfig->max_attribute_value }}"
  data-min-total="{{ $attributesConfig->min_total_attributes }}"
  data-max-total="{{ $attributesConfig->max_total_attributes }}"
  data-base-health="{{ $attributesConfig->total_health_base }}"
  data-agility-multiplier="{{ $attributesConfig->agility_multiplier }}"
  data-mental-multiplier="{{ $attributesConfig->mental_multiplier }}"
  data-will-multiplier="{{ $attributesConfig->will_multiplier }}"
  data-strength-multiplier="{{ $attributesConfig->strength_multiplier }}"
  data-armor-multiplier="{{ $attributesConfig->armor_multiplier }}"
>
  @csrf
  @if($hero) @method('PUT') @endif
  
  <x-form.card 
    :submit_label="$submitLabel"
    :cancel_route="$cancelRoute"
  >
    <div class="form-row">
      <x-form.field 
        name="name" 
        label="Nombre del Héroe" 
        :value="$hero->name ?? ''" 
        required
        maxlength="255" 
      />
      
      <x-form.select
        name="gender" 
        label="Género" 
        :value="$hero->gender ?? 'male'"
        :options="['male' => 'Masculino', 'female' => 'Femenino']"
        required
      />
    </div>
    
    <div class="form-row">
      <x-form.select
        name="faction_id" 
        label="Facción" 
        placeholder="Selecciona una facción"
        :value="$hero->faction_id ?? ''"
        :options="$factions->pluck('name', 'id')->toArray()"
      />
      
      <x-form.select
        name="hero_race_id" 
        label="Raza" 
        placeholder="Selecciona una raza"
        :value="$hero->hero_race_id ?? ''"
        :options="$heroRaces->pluck('name', 'id')->toArray()"
        required
      />
      
      <x-form.select
        name="hero_class_id" 
        label="Clase" 
        placeholder="Selecciona una clase"
        :value="$hero->hero_class_id ?? ''"
        :options="$heroClasses->pluck('name', 'id')->toArray()"
        required
      />
    </div>
    
    <x-form.textarea
      name="lore_text" 
      label="Descripción del Héroe" 
      :value="$hero->lore_text ?? ''"
      rows="5"
    />
    
    <div class="form-row attributes-row">
      <x-form.field 
        name="agility" 
        label="Agilidad" 
        type="number"
        :value="$hero->agility ?? 2" 
        required
        min="{{ $attributesConfig->min_attribute_value }}"
        max="{{ $attributesConfig->max_attribute_value }}"
        data-attribute="true"
      />
      
      <x-form.field 
        name="mental" 
        label="Mental" 
        type="number"
        :value="$hero->mental ?? 2" 
        required
        min="{{ $attributesConfig->min_attribute_value }}"
        max="{{ $attributesConfig->max_attribute_value }}"
        data-attribute="true"
      />
      
      <x-form.field 
        name="will" 
        label="Voluntad" 
        type="number"
        :value="$hero->will ?? 2" 
        required
        min="{{ $attributesConfig->min_attribute_value }}"
        max="{{ $attributesConfig->max_attribute_value }}"
        data-attribute="true"
      />
      
      <x-form.field 
        name="strength" 
        label="Fuerza" 
        type="number"
        :value="$hero->strength ?? 2" 
        required
        min="{{ $attributesConfig->min_attribute_value }}"
        max="{{ $attributesConfig->max_attribute_value }}"
        data-attribute="true"
      />
      
      <x-form.field 
        name="armor" 
        label="Armadura" 
        type="number"
        :value="$hero->armor ?? 2" 
        required
        min="{{ $attributesConfig->min_attribute_value }}"
        max="{{ $attributesConfig->max_attribute_value }}"
        data-attribute="true"
      />
    </div>
    
    <p class="form-text">
      Los valores de atributos deben estar entre {{ $attributesConfig->min_attribute_value }} y {{ $attributesConfig->max_attribute_value }}. 
      La suma total debe estar entre {{ $attributesConfig->min_total_attributes }} y {{ $attributesConfig->max_total_attributes }}.
    </p>
    <p class="form-text">
      Total:
      <span id="total-attributes-value" class="{{ isset($errors) && $errors->has('total_attributes') ? 'invalid-total' : '' }}">
        {{ ($hero ? $hero->getTotalAttributePoints() : 10) }}
      </span>
        - Vida:
      <span id="calculated-health">
        {{ ($hero ? $hero->calculateHealth() : 25) }}
      </span>
    </p>  
    @error('total_attributes')
      <div class="attributes-error">{{ $message }}</div>
    @enderror
   
    <x-form.field 
      name="passive_name" 
      label="Nombre de la Pasiva" 
      :value="$hero->passive_name ?? ''" 
    />
    
    <x-form.wysiwyg
      name="passive_description" 
      label="Descripción de la Pasiva" 
      :value="$hero->passive_description ?? ''"
      rows="5"
    />
    
    <x-form.image-uploader
      name="image" 
      label="Imagen del Héroe" 
      :currentImage="$hero->image ?? null"
      help="Formatos: JPEG, PNG, JPG, GIF, SVG. Tamaño máximo: 2MB."
    />
  </x-form.card>
</form>
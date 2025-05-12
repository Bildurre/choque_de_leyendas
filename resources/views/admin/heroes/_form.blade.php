@php
  $submitRoute = isset($hero) 
    ? route('admin.heroes.update', $hero) 
    : route('admin.heroes.store');
  $submitMethod = isset($hero) ? 'PUT' : 'POST';
  $submitLabel = isset($hero) ? __('admin.update') : __('heroes.create');
  
  // Set default selections
  $selectedAbilities = $selectedAbilities ?? [];
@endphp

<form action="{{ $submitRoute }}" method="POST" enctype="multipart/form-data" class="form" id="hero-form">
  @csrf
  @if($submitMethod === 'PUT')
    @method('PUT')
  @endif
  
  <x-form.card :submit_label="$submitLabel" :cancel_route="route('admin.heroes.index')">    
    <div class="form-grid">
      <x-form.multilingual-input
        name="name"
        :label="__('heroes.name')"
        :values="isset($hero) ? $hero->getTranslations('name') : []"
        required
      />
      
      <x-form.multilingual-wysiwyg
        name="lore_text"
        :label="__('heroes.lore_text')"
        :values="isset($hero) ? $hero->getTranslations('lore_text') : []"
      />

      <x-form.multilingual-input
        name="passive_name"
        :label="__('heroes.passive_name')"
        :values="isset($hero) ? $hero->getTranslations('passive_name') : []"
      />
          
      <x-form.multilingual-wysiwyg
        name="passive_description"
        :label="__('heroes.passive_description')"
        :values="isset($hero) ? $hero->getTranslations('passive_description') : []"
      />

      <x-form.select
        name="faction_id"
        :label="__('factions.singular')"
        :options="['' => __('heroes.no_faction')] + $factions->pluck('name', 'id')->toArray()"
        :selected="old('faction_id', isset($hero) ? $hero->faction_id : '')"
      />
        

      <x-form.select
        name="hero_race_id"
        :label="__('hero_races.singular')"
        :options="$heroRaces->pluck('name', 'id')->toArray()"
        :selected="old('hero_race_id', isset($hero) ? $hero->hero_race_id : '')"
        required
      />
          
      <x-form.select
        name="hero_class_id"
        :label="__('hero_classes.singular')"
        :options="$heroClasses->pluck('name', 'id')->toArray()"
        :selected="old('hero_class_id', isset($hero) ? $hero->hero_class_id : '')"
        required
      />
        
      <x-form.select
        name="gender"
        :label="__('heroes.gender')"
        :options="[
          'male' => __('heroes.genders.male'),
          'female' => __('heroes.genders.female')
        ]"
        :selected="old('gender', isset($hero) ? $hero->gender : 'male')"
        required
      />


      <x-form.input
        type="number" 
        name="agility" 
        :label="__('attributes.agility')"
        value="{{ old('agility', isset($hero) ? $hero->agility : 2) }}" 
        min="{{ $attributesConfig->min_attribute_value }}"
        max="{{ $attributesConfig->max_attribute_value }}"
        required
      />
      <x-form.input
        type="number" 
        name="mental" 
        :label="__('attributes.mental')"
        value="{{ old('mental', isset($hero) ? $hero->mental : 2) }}" 
        min="{{ $attributesConfig->min_attribute_value }}"
        max="{{ $attributesConfig->max_attribute_value }}"
        required
      />
      <x-form.input
        type="number" 
        name="will" 
        :label="__('attributes.will')"
        value="{{ old('will', isset($hero) ? $hero->will : 2) }}" 
        min="{{ $attributesConfig->min_attribute_value }}"
        max="{{ $attributesConfig->max_attribute_value }}"
        required
      />

      <x-form.input
        type="number" 
        name="strength" 
        :label="__('attributes.strength')"
        value="{{ old('strength', isset($hero) ? $hero->strength : 2) }}" 
        min="{{ $attributesConfig->min_attribute_value }}"
        max="{{ $attributesConfig->max_attribute_value }}"
        required
      />

      <x-form.input
        type="number" 
        name="armor" 
        :label="__('attributes.armor')"
        value="{{ old('armor', isset($hero) ? $hero->armor : 2) }}" 
        min="{{ $attributesConfig->min_attribute_value }}"
        max="{{ $attributesConfig->max_attribute_value }}"
        required
      />
          
      <x-form.image-upload
        name="image"
        :label="__('heroes.image')"
        :current-image="isset($hero) && $hero->image ? $hero->getImageUrl() : null"
        :remove-name="isset($hero) ? 'remove_image' : null"
      />
    </div>
    
    <fieldset class="form-fieldset">
      <legend>{{ __('hero_abilities.plural') }}</legend>
      
      <x-form.hero-abilities-selector
        :label="__('heroes.select_abilities')"
        :abilities="$heroAbilities"
        :selected="$selectedAbilities"
      />
    </fieldset>
  </x-form.card>
</form>
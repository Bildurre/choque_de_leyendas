@php
  $submitRoute = isset($hero) 
    ? route('admin.heroes.update', $hero) 
    : route('admin.heroes.store');
  $submitMethod = isset($hero) ? 'PUT' : 'POST';
  $submitLabel = isset($hero) ? __('admin.update') : __('entities.heroes.create');
  
  // Set default selections
  $selectedAbilities = $selectedAbilities ?? [];
@endphp

<form action="{{ $submitRoute }}" method="POST" enctype="multipart/form-data" class="form" id="hero-form">
  @csrf
  @if($submitMethod === 'PUT')
    @method('PUT')
  @endif
  
  <x-form.card :submit_label="$submitLabel" :cancel_route="route('admin.heroes.index')">

    <x-form.fieldset :legend="__('entities.heroes.basic_info')">
      <div>
        <x-form.multilingual-input
          name="name"
          :label="__('entities.heroes.name')"
          :values="isset($hero) ? $hero->getTranslations('name') : []"
          required
        />

        <x-form.select
          name="hero_race_id"
          :label="__('entities.hero_races.singular')"
          :options="$heroRaces->pluck('name', 'id')->toArray()"
          :selected="old('hero_race_id', isset($hero) ? $hero->hero_race_id : '')"
          required
        />
          
        <x-form.select
          name="gender"
          :label="__('entities.heroes.gender')"
          :options="[
            'male' => __('entities.heroes.genders.male'),
            'female' => __('entities.heroes.genders.female')
          ]"
          :selected="old('gender', isset($hero) ? $hero->gender : 'male')"
          required
        />
      </div>

      <div>
        <x-form.select
          name="faction_id"
          :label="__('entities.factions.singular')"
          :options="['' => __('entities.heroes.no_faction')] + $factions->pluck('name', 'id')->toArray()"
          :selected="old('faction_id', $selectedFactionId ?? (isset($hero) ? $hero->faction_id : ''))"
          required
        />
            
        <x-form.select
          name="hero_class_id"
          :label="__('entities.hero_classes.singular')"
          :options="$heroClasses->pluck('name', 'id')->toArray()"
          :selected="old('hero_class_id', isset($hero) ? $hero->hero_class_id : '')"
          required
        />

        <x-form.checkbox
          name="is_published"
          :label="__('admin.published')"
          :checked="old('is_published', isset($hero) ? $hero->is_published : false)"
        />
      </div>
          
      <x-form.image-upload
        name="image"
        :label="__('entities.heroes.image')"
        :current-image="isset($hero) && $hero->image ? $hero->getImageUrl() : null"
        :remove-name="isset($hero) ? 'remove_image' : null"
      />
    </x-form.fieldset>

    <x-form.fieldset :legend="__('entities.heroes.attributes.title')">
      <x-form.input
        type="number" 
        name="agility" 
        :label="__('entities.heroes.attributes.agility')"
        value="{{ old('agility', isset($hero) ? $hero->agility : 3) }}" 
        min="{{ $attributesConfig->min_attribute_value }}"
        max="{{ $attributesConfig->max_attribute_value }}"
        required
      />

      <x-form.input
        type="number" 
        name="mental" 
        :label="__('entities.heroes.attributes.mental')"
        value="{{ old('mental', isset($hero) ? $hero->mental : 3) }}" 
        min="{{ $attributesConfig->min_attribute_value }}"
        max="{{ $attributesConfig->max_attribute_value }}"
        required
      />

      <x-form.input
        type="number" 
        name="will" 
        :label="__('entities.heroes.attributes.will')"
        value="{{ old('will', isset($hero) ? $hero->will : 3) }}" 
        min="{{ $attributesConfig->min_attribute_value }}"
        max="{{ $attributesConfig->max_attribute_value }}"
        required
      />

      <x-form.input
        type="number" 
        name="strength" 
        :label="__('entities.heroes.attributes.strength')"
        value="{{ old('strength', isset($hero) ? $hero->strength : 3) }}" 
        min="{{ $attributesConfig->min_attribute_value }}"
        max="{{ $attributesConfig->max_attribute_value }}"
        required
      />

      <x-form.input
        type="number" 
        name="armor" 
        :label="__('entities.heroes.attributes.armor')"
        value="{{ old('armor', isset($hero) ? $hero->armor : 3) }}" 
        min="{{ $attributesConfig->min_attribute_value }}"
        max="{{ $attributesConfig->max_attribute_value }}"
        required
      />
    </x-form.fieldset>

    <x-form.fieldset :legend="__('entities.heroes.passive')">
      <x-form.multilingual-input
        name="passive_name"
        :label="__('entities.heroes.passive_name')"
        :values="isset($hero) ? $hero->getTranslations('passive_name') : []"
      />
          
      <x-form.multilingual-wysiwyg
        name="passive_description"
        :label="__('entities.heroes.passive_description')"
        :values="isset($hero) ? $hero->getTranslations('passive_description') : []"
      />
    </x-form.fieldset>
    
    <x-form.fieldset :legend="__('entities.heroes.actives')">
      <x-form.hero-ability-selector
        :abilities="$heroAbilities"
        :selectedAbilities="$selectedAbilities"
        name="hero_abilities"
      />
    </x-form.fieldset>

    <x-form.fieldset :legend="__('entities.heroes.lore')">
      <x-form.multilingual-wysiwyg
        name="lore_text"
        :label="__('entities.heroes.lore_text')"
        :values="isset($hero) ? $hero->getTranslations('lore_text') : []"
      />

      <x-form.multilingual-wysiwyg
        name="epic_quote"
        :label="__('entities.heroes.epic_quote')"
        :values="isset($hero) ? $hero->getTranslations('epic_quote') : []"
      />
    </x-form.fieldset>
  </x-form.card>
</form>
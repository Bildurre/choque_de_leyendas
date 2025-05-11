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
    <x-slot:header>
      <h2>{{ __('heroes.form_title') }}</h2>
    </x-slot:header>
    
    <div class="form-grid">
      <div>
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
          :upload-url="route('admin.content.images.store')"
          :images-url="route('admin.content.images.index')"
        />
        
        <fieldset class="form-fieldset">
          <legend>{{ __('heroes.passive_ability') }}</legend>
          
          <x-form.multilingual-input
            name="passive_name"
            :label="__('heroes.passive_name')"
            :values="isset($hero) ? $hero->getTranslations('passive_name') : []"
          />
          
          <x-form.multilingual-wysiwyg
            name="passive_description"
            :label="__('heroes.passive_description')"
            :values="isset($hero) ? $hero->getTranslations('passive_description') : []"
            :upload-url="route('admin.content.images.store')"
            :images-url="route('admin.content.images.index')"
          />
        </fieldset>
      </div>
      
      <div>
        <x-form.select
          name="faction_id"
          :label="__('factions.singular')"
          :options="['' => __('heroes.no_faction')] + $factions->pluck('name', 'id')->toArray()"
          :selected="old('faction_id', isset($hero) ? $hero->faction_id : '')"
        />
        
        <div class="form-row">
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
        </div>
        
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
        
        <fieldset class="form-fieldset" id="attributes-fieldset">
          <legend>{{ __('heroes.attributes') }}</legend>
          
          <div class="attributes-grid">
            <div class="attribute-control">
              <label for="agility" class="attribute-control__label">{{ __('heroes.attributes.agility') }}</label>
              <div class="attribute-control__input">
                <button type="button" class="attribute-control__button attribute-control__button--minus" data-attribute="agility">-</button>
                <input 
                  type="number" 
                  name="agility" 
                  id="agility" 
                  value="{{ old('agility', isset($hero) ? $hero->agility : 2) }}" 
                  min="{{ $attributesConfig->min_attribute_value }}"
                  max="{{ $attributesConfig->max_attribute_value }}"
                  readonly 
                  class="attribute-control__number" 
                  required
                >
                <button type="button" class="attribute-control__button attribute-control__button--plus" data-attribute="agility">+</button>
              </div>
            </div>
            
            <div class="attribute-control">
              <label for="mental" class="attribute-control__label">{{ __('heroes.attributes.mental') }}</label>
              <div class="attribute-control__input">
                <button type="button" class="attribute-control__button attribute-control__button--minus" data-attribute="mental">-</button>
                <input 
                  type="number" 
                  name="mental" 
                  id="mental" 
                  value="{{ old('mental', isset($hero) ? $hero->mental : 2) }}" 
                  min="{{ $attributesConfig->min_attribute_value }}"
                  max="{{ $attributesConfig->max_attribute_value }}"
                  readonly 
                  class="attribute-control__number" 
                  required
                >
                <button type="button" class="attribute-control__button attribute-control__button--plus" data-attribute="mental">+</button>
              </div>
            </div>
            
            <div class="attribute-control">
              <label for="will" class="attribute-control__label">{{ __('heroes.attributes.will') }}</label>
              <div class="attribute-control__input">
                <button type="button" class="attribute-control__button attribute-control__button--minus" data-attribute="will">-</button>
                <input 
                  type="number" 
                  name="will" 
                  id="will" 
                  value="{{ old('will', isset($hero) ? $hero->will : 2) }}" 
                  min="{{ $attributesConfig->min_attribute_value }}"
                  max="{{ $attributesConfig->max_attribute_value }}"
                  readonly 
                  class="attribute-control__number" 
                  required
                >
                <button type="button" class="attribute-control__button attribute-control__button--plus" data-attribute="will">+</button>
              </div>
            </div>
            
            <div class="attribute-control">
              <label for="strength" class="attribute-control__label">{{ __('heroes.attributes.strength') }}</label>
              <div class="attribute-control__input">
                <button type="button" class="attribute-control__button attribute-control__button--minus" data-attribute="strength">-</button>
                <input 
                  type="number" 
                  name="strength" 
                  id="strength" 
                  value="{{ old('strength', isset($hero) ? $hero->strength : 2) }}" 
                  min="{{ $attributesConfig->min_attribute_value }}"
                  max="{{ $attributesConfig->max_attribute_value }}"
                  readonly 
                  class="attribute-control__number" 
                  required
                >
                <button type="button" class="attribute-control__button attribute-control__button--plus" data-attribute="strength">+</button>
              </div>
            </div>
            
            <div class="attribute-control">
              <label for="armor" class="attribute-control__label">{{ __('heroes.attributes.armor') }}</label>
              <div class="attribute-control__input">
                <button type="button" class="attribute-control__button attribute-control__button--minus" data-attribute="armor">-</button>
                <input 
                  type="number" 
                  name="armor" 
                  id="armor" 
                  value="{{ old('armor', isset($hero) ? $hero->armor : 2) }}" 
                  min="{{ $attributesConfig->min_attribute_value }}"
                  max="{{ $attributesConfig->max_attribute_value }}"
                  readonly 
                  class="attribute-control__number" 
                  required
                >
                <button type="button" class="attribute-control__button attribute-control__button--plus" data-attribute="armor">+</button>
              </div>
            </div>
          </div>
          
          <div class="attributes-summary">
            <div class="attributes-summary__item">
              <span class="attributes-summary__label">{{ __('heroes.total_attributes') }}:</span>
              <span class="attributes-summary__value" id="total-attributes">
                {{ old('agility', isset($hero) ? $hero->agility : 2) + 
                   old('mental', isset($hero) ? $hero->mental : 2) + 
                   old('will', isset($hero) ? $hero->will : 2) + 
                   old('strength', isset($hero) ? $hero->strength : 2) + 
                   old('armor', isset($hero) ? $hero->armor : 2) }}
              </span>
              <span class="attributes-summary__limits">
                ({{ $attributesConfig->min_total_attributes }} - {{ $attributesConfig->max_total_attributes }})
              </span>
            </div>
            
            <div class="attributes-summary__item">
              <span class="attributes-summary__label">{{ __('heroes.attributes.health') }}:</span>
              <span class="attributes-summary__value" id="calculated-health">
                {{ isset($hero) ? $hero->health : 0 }}
              </span>
            </div>
            
            <div class="attributes-summary__points">
              <span class="attributes-summary__label">{{ __('heroes.attribute_points_available') }}:</span>
              <span class="attributes-summary__value" id="points-available">
                {{ $attributesConfig->max_total_attributes - (
                   old('agility', isset($hero) ? $hero->agility : 2) + 
                   old('mental', isset($hero) ? $hero->mental : 2) + 
                   old('will', isset($hero) ? $hero->will : 2) + 
                   old('strength', isset($hero) ? $hero->strength : 2) + 
                   old('armor', isset($hero) ? $hero->armor : 2)
                ) }}
              </span>
            </div>
          </div>
        </fieldset>
        
        <x-form.image-upload
          name="image"
          :label="__('heroes.image')"
          :current-image="isset($hero) && $hero->image ? $hero->getImageUrl() : null"
          :remove-name="isset($hero) ? 'remove_image' : null"
        />
      </div>
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
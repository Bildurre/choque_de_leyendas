@php
  $submitRoute = isset($card) 
    ? route('admin.cards.update', $card) 
    : route('admin.cards.store');
  $submitMethod = isset($card) ? 'PUT' : 'POST';
  $submitLabel = isset($card) ? __('admin.update') : __('cards.create');
@endphp

<form action="{{ $submitRoute }}" method="POST" enctype="multipart/form-data" class="form">
  @csrf
  @if($submitMethod === 'PUT')
    @method('PUT')
  @endif
  
  <x-form.card :submit_label="$submitLabel" :cancel_route="route('admin.cards.index')">
    <x-slot:header>
      <h2>{{ __('cards.form_title') }}</h2>
    </x-slot:header>
    
    <div class="form-grid">
      <div>
        <x-form.multilingual-input
          name="name"
          :label="__('cards.name')"
          :values="isset($card) ? $card->getTranslations('name') : []"
          required
        />
        
        <x-form.select
          name="card_type_id"
          :label="__('card_types.singular')"
          :options="$cardTypes->pluck('name', 'id')->toArray()"
          :selected="old('card_type_id', isset($card) ? $card->card_type_id : '')"
          required
        />
        
        <x-form.select
          name="faction_id"
          :label="__('factions.singular')"
          :options="['' => __('cards.no_faction')] + $factions->pluck('name', 'id')->toArray()"
          :selected="old('faction_id', isset($card) ? $card->faction_id : '')"
        />
        
        <x-form.multilingual-wysiwyg
          name="lore_text"
          :label="__('cards.lore_text')"
          :values="isset($card) ? $card->getTranslations('lore_text') : []"
          :upload-url="route('admin.content.images.store')"
          :images-url="route('admin.content.images.index')"
        />
        
        <x-form.multilingual-wysiwyg
          name="effect"
          :label="__('cards.effect')"
          :values="isset($card) ? $card->getTranslations('effect') : []"
          :upload-url="route('admin.content.images.store')"
          :images-url="route('admin.content.images.index')"
        />
        
        <x-form.multilingual-wysiwyg
          name="restriction"
          :label="__('cards.restriction')"
          :values="isset($card) ? $card->getTranslations('restriction') : []"
          :upload-url="route('admin.content.images.store')"
          :images-url="route('admin.content.images.index')"
        />
      </div>
      
      <div>
        <x-form.cost-input
          name="cost"
          :label="__('cards.cost')"
          :value="old('cost', isset($card) ? $card->cost : '')"
          :help="__('cards.cost_help')"
        />
        
        <div class="card-types-container">
          <div class="equipment-container" id="equipment-container">
            <x-form.select
              name="equipment_type_id"
              :label="__('equipment_types.singular')"
              :options="['' => __('cards.no_equipment_type')] + $equipmentTypes->pluck('name', 'id')->toArray()"
              :selected="old('equipment_type_id', isset($card) ? $card->equipment_type_id : '')"
            />
            
            <div id="hands-container" style="display: none;">
              <x-form.select
                name="hands"
                :label="__('cards.hands')"
                :options="['' => __('cards.select_hands'), '1' => __('cards.one_hand'), '2' => __('cards.two_hands')]"
                :selected="old('hands', isset($card) ? $card->hands : '')"
              />
            </div>
          </div>
          
          <div class="attack-container" id="attack-container">
            <x-form.select
              name="attack_range_id"
              :label="__('attack_ranges.singular')"
              :options="['' => __('cards.no_attack_range')] + $attackRanges->pluck('name', 'id')->toArray()"
              :selected="old('attack_range_id', isset($card) ? $card->attack_range_id : '')"
            />
            
            <x-form.select
              name="attack_subtype_id"
              :label="__('attack_subtypes.singular')"
              :options="['' => __('cards.no_attack_subtype')] + $attackSubtypes->pluck('name', 'id')->toArray()"
              :selected="old('attack_subtype_id', isset($card) ? $card->attack_subtype_id : '')"
            />
            
            <div id="area-container" style="display: none;">
              <x-form.checkbox
                name="area"
                :label="__('cards.is_area_attack')"
                :checked="old('area', isset($card) ? $card->area : false)"
              />
            </div>
          </div>
          
          <div class="hero-ability-container" id="hero-ability-container">
            <x-form.select
              name="hero_ability_id"
              :label="__('hero_abilities.singular')"
              :options="['' => __('cards.no_hero_ability')] + $heroAbilities->pluck('name', 'id')->toArray()"
              :selected="old('hero_ability_id', isset($card) ? $card->hero_ability_id : '')"
            />
          </div>
        </div>
        
        <x-form.image-upload
          name="image"
          :label="__('cards.image')"
          :current-image="isset($card) && $card->image ? $card->getImageUrl() : null"
          :remove-name="isset($card) ? 'remove_image' : null"
        />
      </div>
    </div>
  </x-form.card>
</form>
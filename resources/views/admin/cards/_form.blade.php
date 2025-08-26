@php
  $submitRoute = isset($card) 
    ? route('admin.cards.update', $card) 
    : route('admin.cards.store');
  $submitMethod = isset($card) ? 'PUT' : 'POST';
  $submitLabel = isset($card) ? __('admin.update') : __('entities.cards.create');
@endphp

<x-collapsible-section
  id="dice-values" 
  title="{{ __('admin.dice_values.title') }}"
  forceCollapse="true"
>
  @include('admin._dice-values')
</x-collapsible-section>

<form action="{{ $submitRoute }}" method="POST" enctype="multipart/form-data" class="form">
  @csrf
  @if($submitMethod === 'PUT')
    @method('PUT')
  @endif
  
  <x-form.card :submit_label="$submitLabel" :cancel_route="route('admin.cards.index')">
    <x-form.fieldset :legend="__('entities.cards.basic_info')">
      <div>
        <x-form.multilingual-input
          name="name"
          :label="__('entities.cards.name')"
          :values="isset($card) ? $card->getTranslations('name') : []"
          required
        />
          
        <x-form.select
          name="faction_id"
          :label="__('entities.factions.singular')"
          :options="['' => __('entities.cards.no_faction')] + $factions->pluck('name', 'id')->toArray()"
          :selected="old('faction_id', $selectedFactionId ?? (isset($card) ? $card->faction_id : ''))"
          required
        />

        <x-form.cost-input
          name="cost"
          :label="__('entities.cards.cost')"
          :value="old('cost', isset($card) ? $card->cost : '')"
        />

        <x-form.checkbox
          name="is_published"
          :label="__('admin.published')"
          :checked="old('is_published', isset($card) ? $card->is_published : false)"
        />
      </div>

      <div>
        <x-form.checkbox
          name="is_unique"
          :label="__('admin.is_unique')"
          :checked="old('is_unique', isset($card) ? $card->is_unique : false)"
        />

        <x-form.select
          name="card_type_id"
          :label="__('entities.card_types.singular')"
          :options="$cardTypes->pluck('name', 'id')->toArray()"
          :selected="old('card_type_id', isset($card) ? $card->card_type_id : '')"
          required
        />
        
        <x-form.select
          name="equipment_type_id"
          :label="__('entities.equipment_types.singular')"
          :options="['' => __('entities.cards.no_equipment_type')] + $equipmentTypes->pluck('name', 'id')->toArray()"
          :selected="old('equipment_type_id', isset($card) ? $card->equipment_type_id : '')"
        />

        <x-form.select
          name="hero_ability_id"
          :label="__('entities.hero_abilities.singular')"
          :options="['' => __('entities.cards.no_hero_ability')] + $heroAbilities->pluck('name', 'id')->toArray()"
          :selected="old('hero_ability_id', isset($card) ? $card->hero_ability_id : '')"
        />

        <x-form.select
          name="hands"
          :label="__('entities.cards.hands')"
          :options="['' => __('entities.cards.select_hands'), '1' => __('entities.cards.one_hand'), '2' => __('entities.cards.two_hands')]"
          :selected="old('hands', isset($card) ? $card->hands : '')"
        />

        <x-form.select
          name="attack_range_id"
          :label="__('entities.attack_ranges.singular')"
          :options="['' => __('entities.cards.no_attack_range')] + $attackRanges->pluck('name', 'id')->toArray()"
          :selected="old('attack_range_id', isset($card) ? $card->attack_range_id : '')"
        />
              
        <x-form.select
          name="attack_subtype_id"
          :label="__('entities.attack_subtypes.singular')"
          :options="['' => __('entities.cards.no_attack_subtype')] + $attackSubtypes->pluck('name', 'id')->toArray()"
          :selected="old('attack_subtype_id', isset($card) ? $card->attack_subtype_id : '')"
        />
              
        <x-form.checkbox
          name="area"
          :label="__('entities.cards.is_area_attack')"
          :checked="old('area', isset($card) ? $card->area : false)"
        />
      </div>

      <x-form.image-upload
        name="image"
        :label="__('entities.cards.image')"
        :current-image="isset($card) && $card->image ? $card->getImageUrl() : null"
        :remove-name="isset($card) ? 'remove_image' : null"
      />
    </x-form.fieldset>

    <x-form.fieldset :legend="__('entities.cards.effects')">
      <x-form.multilingual-wysiwyg
        name="effect"
        :label="__('entities.cards.effect')"
        :values="isset($card) ? $card->getTranslations('effect') : []"
      />
        
      <x-form.multilingual-wysiwyg
        name="restriction"
        :label="__('entities.cards.restriction')"
        :values="isset($card) ? $card->getTranslations('restriction') : []"
      />
    </x-form.fieldset>

    <x-form.fieldset :legend="__('entities.cards.lore')">
      <x-form.multilingual-wysiwyg
        name="lore_text"
        :label="__('entities.cards.lore_text')"
        :values="isset($card) ? $card->getTranslations('lore_text') : []"
      />

      <x-form.multilingual-wysiwyg
        name="epic_quote"
        :label="__('entities.cards.epic_quote')"
        :values="isset($card) ? $card->getTranslations('epic_quote') : []"
      />
    </x-form.fieldset>
  </x-form.card>
</form>
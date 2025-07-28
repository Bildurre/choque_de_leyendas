@php
  $submitRoute = isset($heroAbility) 
    ? route('admin.hero-abilities.update', $heroAbility) 
    : route('admin.hero-abilities.store');
  $submitMethod = isset($heroAbility) ? 'PUT' : 'POST';
  $submitLabel = isset($heroAbility) ? __('admin.update') : __('entities.hero_abilities.create');
@endphp

<form action="{{ $submitRoute }}" method="POST" class="form">
  @csrf
  @if($submitMethod === 'PUT')
    @method('PUT')
  @endif
  
  <x-form.card :submit_label="$submitLabel" :cancel_route="route('admin.hero-abilities.index')">    
    <div class="form-grid">
      <div>
        <x-form.multilingual-input
          name="name"
          :label="__('entities.hero_abilities.name')"
          :values="isset($heroAbility) ? $heroAbility->getTranslations('name') : []"
          required
        />
      
        <x-form.cost-input
          name="cost"
          :label="__('entities.hero_abilities.cost')"
          :value="old('cost', isset($heroAbility) ? $heroAbility->cost : '')"
          required
        />
          
        <x-form.select
          name="attack_range_id"
          :label="__('entities.attack_ranges.singular')"
          :options="['' => __('entities.hero_abilities.no_attack_range')] + $attackRanges->pluck('name', 'id')->toArray()"
          :selected="old('attack_range_id', isset($heroAbility) ? $heroAbility->attack_range_id : '')"
        />
            
        <x-form.select
          name="attack_subtype_id"
          :label="__('entities.attack_subtypes.singular')"
          :options="['' => __('entities.hero_abilities.no_attack_subtype')] + $attackSubtypes->pluck('name', 'id')->toArray()"
          :selected="old('attack_subtype_id', isset($heroAbility) ? $heroAbility->attack_subtype_id : '')"
        />

        <x-form.checkbox
          name="area"
          :label="__('entities.hero_abilities.is_area_attack')"
          :checked="old('area', isset($heroAbility) ? $heroAbility->area : false)"
        />
      </div>
        
      <x-form.multilingual-wysiwyg
        name="description"
        :label="__('entities.hero_abilities.description')"
        :values="isset($heroAbility) ? $heroAbility->getTranslations('description') : []"
      />
    </div>
  </x-form.card>
</form>
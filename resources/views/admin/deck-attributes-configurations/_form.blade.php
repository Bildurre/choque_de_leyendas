<!-- resources/views/admin/deck-attributes-configurations/_form.blade.php -->
@php
  $submitRoute = isset($configuration) 
    ? route('admin.deck-attributes-configurations.update', $configuration) 
    : route('admin.deck-attributes-configurations.store');
  $submitMethod = isset($configuration) ? 'PUT' : 'POST';
  $submitLabel = isset($configuration) ? __('admin.update') : __('entities.deck_attributes.create');
@endphp

<form action="{{ $submitRoute }}" method="POST" class="form">
  @csrf
  @if($submitMethod === 'PUT')
    @method('PUT')
  @endif
  
  <x-form.card :submit_label="$submitLabel" :cancel_route="route('admin.deck-attributes-configurations.index')">
    <div class="form-grid">
      <x-form.select
        name="game_mode_id"
        :label="__('entities.game_modes.singular')"
        :options="$gameModes->pluck('name', 'id')->toArray()"
        :selected="old('game_mode_id', isset($configuration) ? $configuration->game_mode_id : '')"
        :placeholder="__('entities.deck_attributes.select_game_mode')"
      />
      
      <x-form.input
        type="number" 
        name="min_cards" 
        :label="__('entities.deck_attributes.min_cards')"
        value="{{ old('min_cards', isset($configuration) ? $configuration->min_cards : 30) }}" 
        min="1"
        max="100"
        required
      />
      
      <x-form.input
        type="number" 
        name="max_cards" 
        :label="__('entities.deck_attributes.max_cards')"
        value="{{ old('max_cards', isset($configuration) ? $configuration->max_cards : 40) }}" 
        min="1"
        max="100"
        required
      />
      
      <x-form.input
        type="number" 
        name="max_copies_per_card" 
        :label="__('entities.deck_attributes.max_copies_per_card')"
        value="{{ old('max_copies_per_card', isset($configuration) ? $configuration->max_copies_per_card : 2) }}" 
        min="1"
        max="10"
        required
      />
      
      <x-form.input
        type="number" 
        name="max_copies_per_hero" 
        :label="__('entities.deck_attributes.max_copies_per_hero')"
        value="{{ old('max_copies_per_hero', isset($configuration) ? $configuration->max_copies_per_hero : 1) }}" 
        min="1"
        max="10"
        required
      />
      
      <x-form.input
        type="number" 
        name="required_heroes" 
        :label="__('entities.deck_attributes.required_heroes')"
        value="{{ old('required_heroes', isset($configuration) ? $configuration->required_heroes : 1) }}" 
        min="0"
        max="20"
        required
      />
    </div>
  </x-form.card>
</form>
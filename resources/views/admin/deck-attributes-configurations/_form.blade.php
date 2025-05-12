@php
  $submitRoute = isset($configuration) 
    ? route('admin.deck-attributes-configurations.update', $configuration) 
    : route('admin.deck-attributes-configurations.store');
  $submitMethod = isset($configuration) ? 'PUT' : 'POST';
  $submitLabel = isset($configuration) ? __('admin.update') : __('admin.create');
@endphp

<form action="{{ $submitRoute }}" method="POST" class="form deck-attributes-form">
  @csrf
  @if($submitMethod === 'PUT')
    @method('PUT')
  @endif
  
  <x-form.card :submit_label="$submitLabel" :cancel_route="route('admin.deck-attributes-configurations.index')">
    <div class="form-grid">
      @if(!isset($configuration) || !$configuration->exists)
        <x-form.select
          name="game_mode_id"
          :label="__('game_modes.singular')"
          :options="$gameModes->pluck('name', 'id')->toArray()"
          :selected="old('game_mode_id', isset($configuration) ? $configuration->game_mode_id : '')"
          required
        />
      @else
        <div class="form-field">
          <label class="form-label">{{ __('game_modes.singular') }}</label>
          <div class="form-static-value">{{ $configuration->gameMode->name }}</div>
          <input type="hidden" name="game_mode_id" value="{{ $configuration->game_mode_id }}">
        </div>
      @endif

      <x-form.input
        type="number"
        name="min_cards"
        :label="__('deck_attributes.min_cards')"
        :value="old('min_cards', isset($configuration) ? $configuration->min_cards : 30)"
        required
        min="1"
        max="100"
      />

      <x-form.input
        type="number"
        name="max_cards"
        :label="__('deck_attributes.max_cards')"
        :value="old('max_cards', isset($configuration) ? $configuration->max_cards : 40)"
        required
        min="1"
        max="200"
      />

      <x-form.input
        type="number"
        name="max_copies_per_card"
        :label="__('deck_attributes.max_copies_per_card')"
        :value="old('max_copies_per_card', isset($configuration) ? $configuration->max_copies_per_card : 2)"
        required
        min="1"
        max="10"
      />

      <x-form.input
        type="number"
        name="max_copies_per_hero"
        :label="__('deck_attributes.max_copies_per_hero')"
        :value="old('max_copies_per_hero', isset($configuration) ? $configuration->max_copies_per_hero : 1)"
        required
        min="1"
        max="5"
      />
    </div>
  </x-form.card>
</form>
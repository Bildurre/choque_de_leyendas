@php
  $submitRoute = isset($gameMode) 
    ? route('admin.game-modes.update', $gameMode) 
    : route('admin.game-modes.store');
  $submitMethod = isset($gameMode) ? 'PUT' : 'POST';
  $submitLabel = isset($gameMode) ? __('admin.update') : __('entities.game_modes.create');
@endphp

<form action="{{ $submitRoute }}" method="POST" class="form">
  @csrf
  @if($submitMethod === 'PUT')
    @method('PUT')
  @endif
  
  <x-form.card :submit_label="$submitLabel" :cancel_route="route('admin.game-modes.index')">    
    <div class="form-grid">
      <x-form.multilingual-input
        name="name"
        :label="__('entities.game_modes.name')"
        :values="isset($gameMode) ? $gameMode->getTranslations('name') : []"
        required
      />
      
      <x-form.multilingual-wysiwyg
        name="description"
        :label="__('entities.game_modes.description')"
        :values="isset($gameMode) ? $gameMode->getTranslations('description') : []"
      />
    </div>
  </x-form.card>
</form>
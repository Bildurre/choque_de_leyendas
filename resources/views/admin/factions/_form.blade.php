@php
  $submitRoute = isset($faction) 
    ? route('admin.factions.update', $faction) 
    : route('admin.factions.store');
  $submitMethod = isset($faction) ? 'PUT' : 'POST';
  $submitLabel = isset($faction) ? __('admin.update') : __('entities.factions.create');
@endphp

<form action="{{ $submitRoute }}" method="POST" enctype="multipart/form-data" class="form">
  @csrf
  @if($submitMethod === 'PUT')
    @method('PUT')
  @endif
  
  <x-form.card :submit_label="$submitLabel" :cancel_route="route('admin.factions.index')">
    <div class="form-grid">
      <x-form.multilingual-input
        name="name"
        :label="__('entities.factions.name')"
        :values="isset($faction) ? $faction->getTranslations('name') : []"
        required
      />
      
      <x-form.multilingual-wysiwyg
        name="lore_text"
        :label="__('entities.factions.lore_text')"
        :values="isset($faction) ? $faction->getTranslations('lore_text') : []"
      />

      <x-form.multilingual-wysiwyg
        name="epic_quote"
        :label="__('entities.factions.epic_quote')"
        :values="isset($card) ? $card->getTranslations('epic_quote') : []"
      />
    
      <x-form.color-picker
        name="color"
        :label="__('entities.factions.color')"
        :value="old('color', isset($faction) ? $faction->color : '#000000')"
        required
      />
      
      <x-form.image-upload
        name="icon"
        :label="__('entities.factions.icon')"
        :current-image="isset($faction) && $faction->icon ? $faction->getImageUrl() : null"
        :remove-name="isset($faction) ? 'remove_icon' : null"
      />

      <x-form.checkbox
        name="is_published"
        :label="__('admin.published')"
        :checked="old('is_published', isset($faction) ? $faction->is_published : false)"
      />
    </div>
  </x-form.card>
</form>
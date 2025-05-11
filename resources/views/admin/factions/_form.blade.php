@php
  $submitRoute = isset($faction) 
    ? route('admin.factions.update', $faction) 
    : route('admin.factions.store');
  $submitMethod = isset($faction) ? 'PUT' : 'POST';
  $submitLabel = isset($faction) ? __('admin.update') : __('factions.create');
@endphp

<form action="{{ $submitRoute }}" method="POST" enctype="multipart/form-data" class="form">
  @csrf
  @if($submitMethod === 'PUT')
    @method('PUT')
  @endif
  
  <x-form.card :submit_label="$submitLabel" :cancel_route="route('admin.factions.index')">
    <x-slot:header>
      <h2>{{ __('factions.form_title') }}</h2>
    </x-slot:header>
    
    <div class="form-grid">
      <div>
        <x-form.multilingual-input
          name="name"
          :label="__('factions.name')"
          :values="isset($faction) ? $faction->getTranslations('name') : []"
          required
        />
        
        <x-form.multilingual-wysiwyg
          name="lore_text"
          :label="__('factions.lore_text')"
          :values="isset($faction) ? $faction->getTranslations('lore_text') : []"
        />
      </div>
      
      <div>
        <x-form.color-picker
          name="color"
          :label="__('factions.color')"
          :value="old('color', isset($faction) ? $faction->color : '#000000')"
          required
        />
        
        <x-form.image-upload
          name="icon"
          :label="__('factions.icon')"
          :current-image="isset($faction) && $faction->icon ? $faction->getImageUrl() : null"
          :remove-name="isset($faction) ? 'remove_icon' : null"
        />
      </div>
    </div>
  </x-form.card>
</form>
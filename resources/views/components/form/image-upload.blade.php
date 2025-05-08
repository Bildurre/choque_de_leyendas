@props([
  'name',
  'label' => null,
  'required' => false,
  'currentImage' => null,
  'removeName' => null
])

<div class="form-field form-field--image-upload">
  @if($label)
    <x-form.label :for="$name" :required="$required">{{ $label }}</x-form.label>
  @endif
  
  <div class="image-upload" data-name="{{ $name }}">
    <input 
      type="file" 
      id="{{ $name }}" 
      name="{{ $name }}" 
      class="image-upload__input" 
      accept="image/*" 
      {{ $required ? 'required' : '' }}
    />
    
    @if($removeName)
      <input type="hidden" name="{{ $removeName }}" value="0" class="image-upload__remove-flag" />
    @endif
    
    <div class="image-upload__dropzone" tabindex="0">
      <div class="image-upload__placeholder">
        <div class="image-upload__icon">
          <x-icon name="upload" size="lg" />
        </div>
        <div class="image-upload__text">
          <p>{{ __('admin.drag_image_here') }}</p>
          <p class="image-upload__browse-text">{{ __('admin.or_click_to_browse') }}</p>
        </div>
      </div>
      
      <div class="image-upload__preview" style="{{ $currentImage ? '' : 'display: none;' }}">
        <img src="{{ $currentImage }}" alt="{{ $label ?? 'Imagen' }}" class="image-upload__image" />
        <button type="button" class="image-upload__remove action-button action-button--delete" title="{{ __('admin.remove_image') }}">
          <x-icon name="trash" size="sm" />
        </button>
      </div>
    </div>
    
    <x-form.error :name="$name" />
  </div>
</div>
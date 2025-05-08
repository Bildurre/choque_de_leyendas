@props([
  'name',
  'label' => null,
  'required' => false,
  'currentImage' => null,
  'removeName' => null
])

<div class="form-field">
  @if($label)
    <x-form.label :for="$name" :required="$required">{{ $label }}</x-form.label>
  @endif
  
  <div class="image-upload {{ $currentImage ? 'has-image' : '' }}">
    <!-- Área de arrastrar y soltar con superposición -->
    <div class="image-upload__dropzone">
      <!-- Vista previa superpuesta (visible solo si hay imagen) -->
      <div class="image-upload__preview-container" {{ !$currentImage ? 'style=display:none' : '' }}>
        <img src="{{ $currentImage }}" alt="{{ $label ?? 'Current image' }}" class="image-upload__preview">
        
        @if($removeName)
          <!-- Botón para eliminar imagen (reemplaza el checkbox) -->
          <button type="button" class="image-upload__remove-btn action-button action-button--delete" title="{{ __('admin.remove_image') }}">
            <x-icon name="trash" size="sm" />
          </button>
          
          <!-- Input oculto para indicar eliminación -->
          <input type="hidden" name="{{ $removeName }}" value="0" class="image-upload__remove-flag">
        @endif
      </div>
      
      <!-- Placeholder (visible solo cuando no hay imagen) -->
      <div class="image-upload__placeholder">
        <div class="image-upload__icon">
          <x-icon name="upload" size="md" />
        </div>
        <div class="image-upload__text">
          <p>{{ __('admin.drag_image_here') }}</p>
          <p class="image-upload__browse-text">{{ __('admin.or_click_to_browse') }}</p>
        </div>
      </div>
      
      <!-- Input oculto -->
      <input 
        type="file" 
        name="{{ $name }}" 
        id="{{ $name }}" 
        class="image-upload__input"
        accept="image/*"
        {{ $required ? 'required' : '' }}
      >
    </div>
    
    <x-form.error :name="$name" />
  </div>
</div>
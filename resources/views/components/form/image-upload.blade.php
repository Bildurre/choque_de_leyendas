@props([
  'name',
  'label' => null,
  'currentImage' => null,
  'removeName' => null,
  'required' => false
])

<div class="form-field form-field--image">
  @if($label)
    <x-form.label :for="$name" :required="$required">{{ $label }}</x-form.label>
  @endif

  <div class="image-upload">
    @if($currentImage)
      <div class="image-upload__preview">
        <img src="{{ $currentImage }}" alt="{{ $label }}" class="image-upload__img">
        
        @if($removeName)
          <div class="image-upload__remove">
            <label class="image-upload__remove-label">
              <input type="checkbox" name="{{ $removeName }}" value="1" class="image-upload__remove-input">
              <span>{{ __('admin.remove_image') }}</span>
            </label>
          </div>
        @endif
      </div>
    @endif
    
    <div class="image-upload__input">
      <input 
        type="file" 
        name="{{ $name }}" 
        id="{{ $name }}"
        class="form-input-file" 
        {{ $required && !$currentImage ? 'required' : '' }}
        accept="image/*"
        {{ $attributes }}
      >
      
      <div class="image-upload__help">
        {{ __('admin.upload_image_help') }}
      </div>
    </div>
  </div>
  
  <x-form.error :name="$name" />
</div>
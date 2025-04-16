@props([
  'name' => 'icon',
  'label' => 'Imagen',
  'required' => false,
  'currentImage' => null,
  'acceptFormats' => 'image/*',
  'help' => null
])

<x-form.group>
  <x-form.label :for="$name" :required="$required">
    {{ $label }}
  </x-form.label>
  
  <div class="image-uploader @if($errors->has($name)) is-invalid @endif" id="{{ $name }}-uploader">
    <!-- Área para drag and drop -->
    <div class="dropzone" id="{{ $name }}-dropzone">
      <input 
        type="file" 
        name="{{ $name }}" 
        id="{{ $name }}" 
        class="dropzone-input"
        accept="{{ $acceptFormats }}"
      >
      
      <!-- Previsualización -->
      <div class="preview-container" id="{{ $name }}-preview-container">
        <img 
          id="{{ $name }}-preview" 
          src="{{ $currentImage ? asset('storage/' . $currentImage) : '' }}" 
          class="image-preview {{ $currentImage ? 'active' : '' }}"
          alt="Vista previa"
        >
        
        <!-- Icono de placeholder y texto de instrucciones -->
        <div class="uploader-placeholder {{ $currentImage ? 'hidden' : '' }}">
          <x-icon name="upload" />
          <span class="uploader-text">
            Arrastra una imagen aquí o haz clic para explorar
          </span>
          <span class="uploader-formats">
            Formatos: JPEG, PNG, GIF, SVG | Máx: 2MB
          </span>
        </div>
        
        <!-- Botón para eliminar la imagen -->
        <button 
          type="button" 
          class="remove-image {{ $currentImage || false ? '' : 'hidden' }}" 
          id="{{ $name }}-remove"
          data-input="{{ $name }}"
        >
          <x-icon name="delete" />
        </button>
      </div>
    </div>
  </div>
  
  <x-form.error :name="$name" />
  
  <x-form.help :text="$help" />
  
  <!-- Campo oculto para marcar la eliminación -->
  <input type="hidden" name="remove_{{ $name }}" id="remove_{{ $name }}" value="0">
</x-form.group>
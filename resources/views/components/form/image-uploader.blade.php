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
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
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
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg>
        </button>
      </div>
    </div>
  </div>
  
  <x-form.error :name="$name" />
  
  <x-form.help :text="$help" />
  
  <!-- Campo oculto para marcar la eliminación -->
  <input type="hidden" name="remove_{{ $name }}" id="remove_{{ $name }}" value="0">
</x-form.group>
@props(['heroSuperclass' => null, 'submitLabel' => 'Guardar', 'cancelRoute' => null])

<form 
  action="{{ $heroSuperclass ? route('admin.hero-superclasses.update', $heroSuperclass) : route('admin.hero-superclasses.store') }}" 
  method="POST" 
  enctype="multipart/form-data"
  class="hero-superclass-form"
>
  @csrf
  @if($heroSuperclass) @method('PUT') @endif
  
  <x-form.card 
    :submit_label="$submitLabel"
    :cancel_route="$cancelRoute"
  >
    <div class="form-row">
      <x-form.translate-field 
        name="name" 
        label="Nombre de la Superclase" 
        :value="$heroSuperclass ? $heroSuperclass->getTranslations('name') : []" 
        required
        max="255" 
      />
      
      <x-form.image-uploader
        name="icon" 
        label="Icono" 
        :currentImage="$heroSuperclass->icon ?? null"
      />
    </div>
  </x-form.card>
</form>
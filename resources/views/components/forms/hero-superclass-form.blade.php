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
    <x-form.field 
      name="name" 
      label="Nombre de la Superclase" 
      :value="$heroSuperclass->name ?? ''" 
      :required="true" 
      maxlength="255" 
    />
    
    <x-form.image-uploader
      name="icon" 
      label="Icono" 
      :currentImage="$heroSuperclass->icon ?? null"
      acceptFormats="image/jpeg,image/png,image/gif,image/svg+xml"
      help="Sube un icono representativo para esta superclase"
    />
  </x-form.card>
</form>
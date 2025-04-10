@props(['heroSuperclass' => null, 'submitLabel' => 'Guardar', 'cancelRoute' => null])

<form 
  action="{{ $hero-superclass ? route('admin.hero-superclasses.update', $hero-superclass) : route('admin.hero-superclasses.store') }}" 
  method="POST" 
  class="hero-superclass-form"
>
  @csrf
  @if($hero-superclass) @method('PUT') @endif
  
  <x-form-card 
    :submit_label="$submitLabel"
    :cancel_route="$cancelRoute"
  >
    <x-form.field 
      name="name" 
      label="Nombre de la Superclase" 
      :value="$hero-superclass->name ?? ''" 
      :required="true" 
      maxlength="255" 
    />
    
    <x-form.field 
      name="color" 
      label="Color" 
      type="color" 
      :value="$hero-superclass->color ?? '#3d3df5'" 
      :required="true" 
      help="Selecciona un color representativo para esta superclase"
    />
  </x-form-card>
</form>
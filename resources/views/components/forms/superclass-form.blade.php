@props(['superclass' => null, 'submitLabel' => 'Guardar', 'cancelRoute' => null])

<form 
  action="{{ $superclass ? route('admin.superclasses.update', $superclass) : route('admin.superclasses.store') }}" 
  method="POST" 
  class="superclass-form"
>
  @csrf
  @if($superclass) @method('PUT') @endif
  
  <x-form-card 
    :submit_label="$submitLabel"
    :cancel_route="$cancelRoute"
  >
    <x-form.field 
      name="name" 
      label="Nombre de la Superclase" 
      :value="$superclass->name ?? ''" 
      :required="true" 
      maxlength="255" 
    />

    <x-form.field 
      name="description" 
      label="Descripción"
      type="textarea" 
      :value="$superclass->description ?? ''" 
      rows="4" 
    />
    
    <x-form.field 
      name="color" 
      label="Color" 
      type="color" 
      :value="$superclass->color ?? '#3d3df5'" 
      :required="true" 
      help="Selecciona un color representativo para esta superclase"
    />
  </x-form-card>
</form>
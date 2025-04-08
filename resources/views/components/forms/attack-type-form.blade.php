@props(['attackType' => null, 'submitLabel' => 'Guardar', 'cancelRoute' => null])

<form 
  action="{{ $attackType ? route('admin.attack-types.update', $attackType) : route('admin.attack-types.store') }}" 
  method="POST" 
  class="attack-type-form"
>
  @csrf
  @if($attackType) @method('PUT') @endif
  
  <x-form-card 
    :submit_label="$submitLabel"
    :cancel_route="$cancelRoute"
  >
    <div class="form-section">
      <x-form.field 
        name="name" 
        label="Nombre del Tipo" 
        :value="$attackType->name ?? ''" 
        :required="true" 
        maxlength="255"
      />

      <x-form.field 
        name="description" 
        label="Descripción" 
        type="textarea" 
        :value="$attackType->description ?? ''" 
        rows="4" 
      />

      <x-form.field 
        name="color" 
        label="Color" 
        type="color" 
        :value="$attackType->color ?? '#3d3df5'" 
        :required="true" 
        help="Color representativo para este tipo de ataque"
      />
    </div>
  </x-form-card>
</form>
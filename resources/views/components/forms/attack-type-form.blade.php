@props(['attackType' => null, 'submitLabel' => 'Guardar', 'cancelRoute' => null])

<form 
  action="{{ $attackType ? route('admin.attack-types.update', $attackType) : route('admin.attack-types.store') }}" 
  method="POST" 
  class="attack-type-form"
>
  @csrf
  @if($attackType) @method('PUT') @endif
  
  <x-form.card 
    :submit_label="$submitLabel"
    :cancel_route="$cancelRoute"
  >
    <x-form.field 
      name="name" 
      label="Nombre del Tipo" 
      :value="$attackType->name ?? ''" 
      required
      max="255"
    />
  </x-form.card>
</form>
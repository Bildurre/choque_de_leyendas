@props(['attackSubtype' => null, 'attackTypes' => [], 'submitLabel' => 'Guardar', 'cancelRoute' => null])

<form 
  action="{{ $attackSubtype ? route('admin.attack-subtypes.update', $attackSubtype) : route('admin.attack-subtypes.store') }}" 
  method="POST" 
  class="attack-subtype-form"
>
  @csrf
  @if($attackSubtype) @method('PUT') @endif
  
  <x-form.card 
    :submit_label="$submitLabel"
    :cancel_route="$cancelRoute"
  >
    <x-form.field 
      name="name" 
      label="Nombre del Subtipo" 
      :value="$attackSubtype->name ?? ''" 
      required
      max="255" 
    />
  </x-form.card>
</form>
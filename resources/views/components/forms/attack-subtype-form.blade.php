@props(['attackSubtype' => null, 'attackTypes' => [], 'submitLabel' => 'Guardar', 'cancelRoute' => null])

<form 
  action="{{ $attackSubtype ? route('admin.attack-subtypes.update', $attackSubtype) : route('admin.attack-subtypes.store') }}" 
  method="POST" 
  class="attack-subtype-form"
>
  @csrf
  @if($attackSubtype) @method('PUT') @endif
  
  <x-form-card 
    :submit_label="$submitLabel"
    :cancel_route="$cancelRoute"
  >
    <div class="form-section">
      <x-form.field 
        name="name" 
        label="Nombre del Subtipo" 
        :value="$attackSubtype->name ?? ''" 
        :required="true" 
        maxlength="255" 
      />

      <x-form.field 
        name="attack_type_id" 
        label="Tipo de Ataque" 
        type="select"
        :value="$attackSubtype->attack_type_id ?? ''"
        :required="true"
        :options="$attackTypes->pluck('name', 'id')->toArray()"
        placeholder="Selecciona un tipo de ataque"
        help="El tipo principal al que pertenece este subtipo"
      />

      <x-form.field 
        name="color" 
        label="Color" 
        type="color" 
        :value="$attackSubtype->color ?? ''" 
        help="Color personalizado para este subtipo. Si se deja vacío, heredará el color del tipo principal."
      />
    </div>
  </x-form-card>
</form>
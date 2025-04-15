@props(['attackRange' => null, 'submitLabel' => 'Guardar', 'cancelRoute' => null])

<form 
  action="{{ $attackRange ? route('admin.attack-ranges.update', $attackRange) : route('admin.attack-ranges.store') }}" 
  method="POST" 
  enctype="multipart/form-data" 
  class="attack-range-form"
>
  @csrf
  @if($attackRange) @method('PUT') @endif
  
  <x-form.card 
    :submit_label="$submitLabel"
    :cancel_route="$cancelRoute"
  >
    <div class="form-row">
      <x-form.field 
        name="name" 
        label="Nombre del Rango" 
        :value="$attackRange->name ?? ''" 
        required
        maxlength="255" 
      />

      <x-form.image-uploader
        name="icon" 
        label="Icono" 
        :currentImage="$attackRange->icon ?? null"
      />
    </div>
  </x-form.card>
</form>
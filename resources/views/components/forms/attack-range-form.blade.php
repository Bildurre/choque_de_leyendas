@props(['attackRange' => null, 'submitLabel' => 'Guardar', 'cancelRoute' => null])

<form 
  action="{{ $attackRange ? route('admin.attack-ranges.update', $attackRange) : route('admin.attack-ranges.store') }}" 
  method="POST" 
  enctype="multipart/form-data" 
  class="attack-range-form"
>
  @csrf
  @if($attackRange) @method('PUT') @endif
  
  <x-form-card 
    :submit_label="$submitLabel"
    :cancel_route="$cancelRoute"
  >
    <div class="form-section">
      <x-form.field 
        name="name" 
        label="Nombre del Rango" 
        :value="$attackRange->name ?? ''" 
        :required="true" 
        maxlength="255" 
      />

      <x-image-uploader
        name="icon" 
        label="Icono" 
        :currentImage="$attackRange->icon ?? null"
        acceptFormats="image/jpeg,image/png,image/gif,image/svg+xml"
        help="Sube un icono representativo para este rango de ataque"
      />
    </div>
  </x-form-card>
</form>
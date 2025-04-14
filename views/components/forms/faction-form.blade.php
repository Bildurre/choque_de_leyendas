@props(['faction' => null, 'submitLabel' => 'Guardar', 'cancelRoute' => null])

<form 
  action="{{ $faction ? route('admin.factions.update', $faction) : route('admin.factions.store') }}" 
  method="POST" 
  enctype="multipart/form-data" 
  class="faction-form"
>
  @csrf
  @if($faction) @method('PUT') @endif
  
  <x-form-card 
    :submit_label="$submitLabel"
    :cancel_route="$cancelRoute"
  >
    <div class="form-section">
      <x-form.field 
        name="name" 
        label="Nombre de la Facción" 
        :value="$faction->name ?? ''" 
        :required="true" 
        maxlength="255" 
      />

      <x-form.field 
        name="lore_text" 
        label="Descripción / Lore" 
        type="textarea" 
        :value="$faction->lore_text ?? ''" 
        rows="5" 
      />

      <x-form.field 
        name="color" 
        label="Color" 
        type="color" 
        :value="$faction->color ?? '#3d3df5'" 
        :required="true" 
        help="Selecciona un color representativo para la facción"
      />

      <x-image-uploader
        name="icon" 
        label="Icono" 
        :currentImage="$faction->icon ?? null"
        acceptFormats="image/jpeg,image/png,image/gif,image/svg+xml"
      />
    </div>
  </x-form-card>
</form>
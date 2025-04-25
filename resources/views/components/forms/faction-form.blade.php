@props(['faction' => null, 'submitLabel' => 'Guardar', 'cancelRoute' => null])

<form 
  action="{{ $faction ? route('admin.factions.update', $faction) : route('admin.factions.store') }}" 
  method="POST" 
  enctype="multipart/form-data" 
  class="faction-form"
>
  @csrf
  @if($faction) @method('PUT') @endif
  
  <x-form.card 
    :submit_label="$submitLabel"
    :cancel_route="$cancelRoute"
  >
    <x-form.field 
      name="name" 
      label="Nombre de la Facción" 
      :value="$faction->name ?? ''" 
      required
      maxlength="255" 
    />

    <x-form.textarea
      name="lore_text" 
      label="Descripción" 
      :value="$faction->lore_text ?? ''"
      :required="true"
    />

    <div class="form-row">
      <x-form.color 
        name="color"
        label="El Color de la Facción"
        :required=true
        :value="$faction->color ?? '#f0f0f0'"
        :required="true"
      />
      
      <x-form.image-uploader 
        name="icon"
        label="Icono de la Facción"
        :currentImage="$faction->icon ?? null"
      />
    </div>
  </x-form.card>
</form>
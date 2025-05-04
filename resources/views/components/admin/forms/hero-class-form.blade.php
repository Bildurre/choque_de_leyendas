@props(['heroClass' => null, 'heroSuperclasses' => [], 'submitLabel' => 'Guardar', 'cancelRoute' => null])

<form 
  action="{{ $heroClass ? route('admin.hero-classes.update', $heroClass) : route('admin.hero-classes.store') }}" 
  method="POST" 
  class="hero-class-form"
>
  @csrf
  @if($heroClass) @method('PUT') @endif
  
  <x-form.card 
    :submit_label="$submitLabel"
    :cancel_route="$cancelRoute"
  >
    <div class="form-row">
      <x-form.translate-field 
        name="name" 
        label="Nombre de la Clase" 
        :value="$heroClass ? $heroClass->getTranslations('name') : []" 
        :required="true"
        max="255"
      />

      <x-form.select 
        name="hero_superclass_id"
        label="Superclase"
        :value="$heroClass->hero_superclass_id ?? ''"
        :required="true"
        :options="$heroSuperclasses->pluck('name', 'id')->toArray()"
      />
    </div>

    <x-form.translate-wysiwyg 
      name="passive" 
      label="Habilidad Pasiva" 
      :value="$heroClass ? $heroClass->getTranslations('passive') : []"
      :required="true"
    />
  </x-form.card>
</form>
@php
  $submitRoute = isset($heroClass) ? route('admin.hero-classes.update', $heroClass) : route('admin.hero-classes.store');
  $submitMethod = isset($heroClass) ? 'PUT' : 'POST';
  $submitLabel = isset($heroClass) ? __('admin.update') : __('hero_classes.create');
  
  // Convertir la colección de superclases a un array para el select
  $superclassOptions = $heroSuperclasses->pluck('name', 'id')->toArray();
@endphp

<form action="{{ $submitRoute }}" method="POST" class="form">
  @csrf
  @if($submitMethod === 'PUT')
    @method('PUT')
  @endif
  
  <div class="form-grid">
    {{-- Campo para el nombre (multilingüe) --}}
    <x-form.multilingual-input
      name="name"
      :label="__('hero_classes.name')"
      :values="$heroClass ? $heroClass->getTranslations('name') : []"
      required
    />
    
    {{-- Selector para superclase usando nuestro nuevo componente --}}
    <x-form.select
      name="hero_superclass_id"
      :label="__('hero_superclasses.singular')"
      :options="$superclassOptions"
      :selected="old('hero_superclass_id', isset($heroClass) ? $heroClass->hero_superclass_id : '')"
      :placeholder="__('admin.select_option')"
      required
    />
    
    {{-- Campo para pasiva (multilingüe con WYSIWYG) --}}
    <x-form.multilingual-wysiwyg
      name="passive"
      :label="__('hero_classes.passive')"
      :values="$heroClass ? $heroClass->getTranslations('passive') : []"
      :upload-url="route('admin.content.images.store')"
      :images-url="route('admin.content.images.index')"
    />
  </div>
  
  <div class="form-buttons">
    <x-button type="submit" variant="primary">{{ $submitLabel }}</x-button>
    <x-button-link :href="route('admin.hero-classes.index')" variant="secondary">{{ __('admin.cancel') }}</x-button-link>
  </div>
</form>
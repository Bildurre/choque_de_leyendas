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
  
  <x-form.card :submit_label="$submitLabel" :cancel_route="route('admin.hero-classes.index')">
    <x-slot:header>
      <h2>{{ __('hero_classes.form_title') }}</h2>
    </x-slot:header>
    
    <x-form.multilingual-input
      name="name"
      :label="__('hero_classes.name')"
      :values="isset($heroClass) ? $heroClass->getTranslations('name') : []"
      required
    />
    
    <x-form.select
      name="hero_superclass_id"
      :label="__('hero_superclasses.singular')"
      :options="$superclassOptions"
      :selected="old('hero_superclass_id', isset($heroClass) ? $heroClass->hero_superclass_id : '')"
      :placeholder="__('admin.select_option')"
      required
    />
    
    <x-form.multilingual-wysiwyg
      name="passive"
      :label="__('hero_classes.passive')"
      :values="isset($heroClass) ? $heroClass->getTranslations('passive') : []"
      :upload-url="route('admin.content.images.store')"
      :images-url="route('admin.content.images.index')"
    />
  </x-form.card>
</form>
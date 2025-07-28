@php
  $submitRoute = isset($heroClass) ? route('admin.hero-classes.update', $heroClass) : route('admin.hero-classes.store');
  $submitMethod = isset($heroClass) ? 'PUT' : 'POST';
  $submitLabel = isset($heroClass) ? __('admin.update') : __('entities.hero_classes.create');
  
  // Convertir la colección de superclases a un array para el select
  $superclassOptions = $heroSuperclasses->pluck('name', 'id')->toArray();
@endphp

<form action="{{ $submitRoute }}" method="POST" class="form">
  @csrf
  @if($submitMethod === 'PUT')
    @method('PUT')
  @endif
  
  <x-form.card :submit_label="$submitLabel" :cancel_route="route('admin.hero-classes.index')">    
    <div class="form-grid">
      <div>
        <x-form.multilingual-input
          name="name"
          :label="__('entities.hero_classes.name')"
          :values="isset($heroClass) ? $heroClass->getTranslations('name') : []"
          required
        />
        
        <x-form.select
          name="hero_superclass_id"
          :label="__('entities.hero_superclasses.singular')"
          :options="$superclassOptions"
          :selected="old('hero_superclass_id', isset($heroClass) ? $heroClass->hero_superclass_id : '')"
          :placeholder="__('admin.select_option')"
          required
        />
      </div>
    
      <x-form.multilingual-wysiwyg
        name="passive"
        :label="__('entities.hero_classes.passive')"
        :values="isset($heroClass) ? $heroClass->getTranslations('passive') : []"
      />
    </div>
  </x-form.card>
</form>
@php
  $submitRoute = isset($heroSuperclass) 
    ? route('admin.hero-superclasses.update', $heroSuperclass) 
    : route('admin.hero-superclasses.store');
  $submitMethod = isset($heroSuperclass) ? 'PUT' : 'POST';
  $submitLabel = isset($heroSuperclass) ? __('admin.update') : __('hero_superclasses.create');
@endphp

<form action="{{ $submitRoute }}" method="POST" enctype="multipart/form-data" class="form">
  @csrf
  @if($submitMethod === 'PUT')
    @method('PUT')
  @endif
  
  <x-form.card :submit_label="$submitLabel" :cancel_route="route('admin.hero-superclasses.index')">
    <x-slot:header>
      <h2>{{ __('hero_superclasses.form_title') }}</h2>
    </x-slot:header>
    
    <div class="form-grid">
      <div>
        <x-form.multilingual-input
          name="name"
          :label="__('hero_superclasses.name')"
          :values="isset($heroSuperclass) ? $heroSuperclass->getTranslations('name') : []"
          required
        />
      </div>
      
      <div>
        <x-form.image-upload
          name="icon"
          :label="__('hero_superclasses.icon')"
          :current-image="isset($heroSuperclass) && $heroSuperclass->icon ? $heroSuperclass->getIconUrl() : null"
          :remove-name="isset($heroSuperclass) ? 'remove_icon' : null"
        />
      </div>
    </div>
  </x-form.card>
</form>
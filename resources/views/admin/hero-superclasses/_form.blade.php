@php
  $submitRoute = isset($heroSuperclass) 
    ? route('admin.hero-superclasses.update', $heroSuperclass) 
    : route('admin.hero-superclasses.store');
  $submitMethod = isset($heroSuperclass) ? 'PUT' : 'POST';
  $submitLabel = isset($heroSuperclass) ? __('admin.update') : __('hero_superclasses.create');
@endphp

<form action="{{ $submitRoute }}" method="POST" class="form">
  @csrf
  @if($submitMethod === 'PUT')
    @method('PUT')
  @endif
  
  <x-form.card :submit_label="$submitLabel" :cancel_route="route('admin.hero-superclasses.index')">    
    <div class="form-grid">
      <x-form.multilingual-input
        name="name"
        :label="__('hero_superclasses.name')"
        :values="isset($heroSuperclass) ? $heroSuperclass->getTranslations('name') : []"
        required
      />
    </div>
  </x-form.card>
</form>
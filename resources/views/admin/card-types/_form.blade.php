@php
  $submitRoute = isset($cardType) 
    ? route('admin.card-types.update', $cardType) 
    : route('admin.card-types.store');
  $submitMethod = isset($cardType) ? 'PUT' : 'POST';
  $submitLabel = isset($cardType) ? __('admin.update') : __('entities.card_types.create');
  
  // Convertir la colección de superclases a un array para el select
  $superclassOptions = $availableSuperclasses->pluck('name', 'id')->toArray();
  $superclassOptions = ['' => __('entities.card_types.no_superclass')] + $superclassOptions;
@endphp

<form action="{{ $submitRoute }}" method="POST" class="form">
  @csrf
  @if($submitMethod === 'PUT')
    @method('PUT')
  @endif
  
  <x-form.card :submit_label="$submitLabel" :cancel_route="route('admin.card-types.index')">
    <div class="form-grid">
      <x-form.multilingual-input
        name="name"
        :label="__('entities.card_types.name')"
        :values="isset($cardType) ? $cardType->getTranslations('name') : []"
        required
      />
      
      <x-form.select
        name="hero_superclass_id"
        :label="__('entities.card_types.hero_superclass')"
        :options="$superclassOptions"
        :selected="old('hero_superclass_id', isset($cardType) ? $cardType->hero_superclass_id : '')"
        :placeholder="__('entities.card_types.select_superclass')"
      />
    </div>
  </x-form.card>
</form>
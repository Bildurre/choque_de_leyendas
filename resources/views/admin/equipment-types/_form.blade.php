@php
  $submitRoute = isset($equipmentType) 
    ? route('admin.equipment-types.update', $equipmentType) 
    : route('admin.equipment-types.store');
  $submitMethod = isset($equipmentType) ? 'PUT' : 'POST';
  $submitLabel = isset($equipmentType) ? __('admin.update') : __('entities.equipment_types.create');
@endphp

<form action="{{ $submitRoute }}" method="POST" class="form">
  @csrf
  @if($submitMethod === 'PUT')
    @method('PUT')
  @endif
  
  <x-form.card :submit_label="$submitLabel" :cancel_route="route('admin.equipment-types.index')">
    <div class="form-grid">
      <x-form.multilingual-input
        name="name"
        :label="__('entities.equipment_types.name')"
        :values="isset($equipmentType) ? $equipmentType->getTranslations('name') : []"
        required
      />
      
      <x-form.select
        name="category"
        :label="__('entities.equipment_types.category')"
        :options="$categories"
        :selected="old('category', isset($equipmentType) ? $equipmentType->category : '')"
        required
      />
    </div>
  </x-form.card>
</form>
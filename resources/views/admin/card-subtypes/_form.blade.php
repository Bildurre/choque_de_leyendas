@php
  $submitRoute = isset($cardSubtype) 
    ? route('admin.card-subtypes.update', $cardSubtype) 
    : route('admin.card-subtypes.store');
  $submitMethod = isset($cardSubtype) ? 'PUT' : 'POST';
  $submitLabel = isset($cardSubtype) ? __('admin.update') : __('entities.card_subtypes.create');
@endphp

<form action="{{ $submitRoute }}" method="POST" class="form">
  @csrf
  @if($submitMethod === 'PUT')
    @method('PUT')
  @endif
  
  <x-form.card :submit_label="$submitLabel" :cancel_route="route('admin.card-subtypes.index')">    
    <x-form.multilingual-input
      name="name"
      :label="__('entities.card_subtypes.name')"
      :values="isset($cardSubtype) ? $cardSubtype->getTranslations('name') : []"
      required
    />
  </x-form.card>
</form>
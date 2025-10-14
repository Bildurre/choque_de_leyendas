@php
  $submitRoute = isset($attackSubtype) 
    ? route('admin.attack-subtypes.update', $attackSubtype) 
    : route('admin.attack-subtypes.store');
  $submitMethod = isset($attackSubtype) ? 'PUT' : 'POST';
  $submitLabel = isset($attackSubtype) ? __('admin.update') : __('entities.attack_subtypes.create');
@endphp

<form action="{{ $submitRoute }}" method="POST" class="form">
  @csrf
  @if($submitMethod === 'PUT')
    @method('PUT')
  @endif
  
  <x-form.card :submit_label="$submitLabel" :cancel_route="route('admin.attack-subtypes.index')">    
    <x-form.multilingual-input
      name="name"
      :label="__('entities.attack_subtypes.name')"
      :values="isset($attackSubtype) ? $attackSubtype->getTranslations('name') : []"
      required
    />
  </x-form.card>
</form>
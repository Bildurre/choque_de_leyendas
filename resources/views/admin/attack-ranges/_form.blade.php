@php
  $submitRoute = isset($attackRange) 
    ? route('admin.attack-ranges.update', $attackRange) 
    : route('admin.attack-ranges.store');
  $submitMethod = isset($attackRange) ? 'PUT' : 'POST';
  $submitLabel = isset($attackRange) ? __('admin.update') : __('attack_ranges.create');
@endphp

<form action="{{ $submitRoute }}" method="POST" class="form">
  @csrf
  @if($submitMethod === 'PUT')
    @method('PUT')
  @endif
  
  <x-form.card :submit_label="$submitLabel" :cancel_route="route('admin.attack-ranges.index')">
    <div class="form-grid">
      <x-form.multilingual-input
        name="name"
        :label="__('attack_ranges.name')"
        :values="isset($attackRange) ? $attackRange->getTranslations('name') : []"
        required
      />
    </div>
  </x-form.card>
</form>
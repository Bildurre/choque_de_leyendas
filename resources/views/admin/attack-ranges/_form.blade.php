@php
  $submitRoute = isset($attackRange) 
    ? route('admin.attack-ranges.update', $attackRange) 
    : route('admin.attack-ranges.store');
  $submitMethod = isset($attackRange) ? 'PUT' : 'POST';
  $submitLabel = isset($attackRange) ? __('admin.update') : __('attack_ranges.create');
@endphp

<form action="{{ $submitRoute }}" method="POST" enctype="multipart/form-data" class="form">
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

      <x-form.image-upload
        name="icon"
        :label="__('attack_ranges.icon')"
        :current-image="isset($attackRange) && $attackRange->icon ? $attackRange->getImageUrl() : null"
        :remove-name="isset($attackRange) ? 'remove_icon' : null"
      />
    </div>
  </x-form.card>
</form>
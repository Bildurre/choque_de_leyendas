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
    <x-slot:header>
      <h2>{{ __('attack_ranges.form_title') }}</h2>
    </x-slot:header>
    
    <div class="form-grid">
      <div>
        <x-form.multilingual-input
          name="name"
          :label="__('attack_ranges.name')"
          :values="isset($attackRange) ? $attackRange->getTranslations('name') : []"
          required
        />
      </div>
      
      <div>
        <x-form.image-upload
          name="icon"
          :label="__('attack_ranges.icon')"
          :current-image="isset($attackRange) && $attackRange->icon ? $attackRange->getIconUrl() : null"
          :remove-name="isset($attackRange) ? 'remove_icon' : null"
        />
      </div>
    </div>
  </x-form.card>
</form>
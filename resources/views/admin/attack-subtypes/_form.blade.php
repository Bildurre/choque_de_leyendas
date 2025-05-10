@php
  $submitRoute = isset($attackSubtype) 
    ? route('admin.attack-subtypes.update', $attackSubtype) 
    : route('admin.attack-subtypes.store');
  $submitMethod = isset($attackSubtype) ? 'PUT' : 'POST';
  $submitLabel = isset($attackSubtype) ? __('admin.update') : __('attack_subtypes.create');
@endphp

<form action="{{ $submitRoute }}" method="POST" class="form">
  @csrf
  @if($submitMethod === 'PUT')
    @method('PUT')
  @endif
  
  <x-form.card :submit_label="$submitLabel" :cancel_route="route('admin.attack-subtypes.index')">
    <x-slot:header>
      <h2>{{ __('attack_subtypes.form_title') }}</h2>
    </x-slot:header>
    
    <div class="form-grid">
      <div>
        <x-form.multilingual-input
          name="name"
          :label="__('attack_subtypes.name')"
          :values="isset($attackSubtype) ? $attackSubtype->getTranslations('name') : []"
          required
        />
      </div>
      
      <div>
        <x-form.select
          name="type"
          :label="__('attack_subtypes.type')"
          :options="$types"
          :selected="old('type', isset($attackSubtype) ? $attackSubtype->type : '')"
          required
        />
      </div>
    </div>
  </x-form.card>
</form>
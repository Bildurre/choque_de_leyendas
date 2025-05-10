@php
  $submitRoute = isset($cardType) 
    ? route('admin.card-types.update', $cardType) 
    : route('admin.card-types.store');
  $submitMethod = isset($cardType) ? 'PUT' : 'POST';
  $submitLabel = isset($cardType) ? __('admin.update') : __('card_types.create');
  
  // Convertir la colección de superclases a un array para el select
  $superclassOptions = $availableSuperclasses->pluck('name', 'id')->toArray();
  $superclassOptions = ['' => __('card_types.no_superclass')] + $superclassOptions;
@endphp

<form action="{{ $submitRoute }}" method="POST" class="form">
  @csrf
  @if($submitMethod === 'PUT')
    @method('PUT')
  @endif
  
  <x-form.card :submit_label="$submitLabel" :cancel_route="route('admin.card-types.index')">
    <x-slot:header>
      <h2>{{ __('card_types.form_title') }}</h2>
    </x-slot:header>
    
    <div class="form-grid">
      <div>
        <x-form.multilingual-input
          name="name"
          :label="__('card_types.name')"
          :values="isset($cardType) ? $cardType->getTranslations('name') : []"
          required
        />
      </div>
      
      <div>
        <x-form.select
          name="hero_superclass_id"
          :label="__('card_types.hero_superclass')"
          :options="$superclassOptions"
          :selected="old('hero_superclass_id', isset($cardType) ? $cardType->hero_superclass_id : '')"
          :placeholder="__('card_types.select_superclass')"
        />
        
        <div class="form-help">
          <p>{{ __('card_types.superclass_help') }}</p>
          @if($availableSuperclasses->isEmpty() && !isset($cardType))
            <p class="form-help__warning">{{ __('card_types.no_available_superclasses') }}</p>
          @endif
        </div>
      </div>
    </div>
  </x-form.card>
</form>
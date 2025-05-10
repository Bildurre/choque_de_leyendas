@php
  $submitRoute = isset($heroRace) 
    ? route('admin.hero-races.update', $heroRace) 
    : route('admin.hero-races.store');
  $submitMethod = isset($heroRace) ? 'PUT' : 'POST';
  $submitLabel = isset($heroRace) ? __('admin.update') : __('hero_races.create');
@endphp

<form action="{{ $submitRoute }}" method="POST" class="form">
  @csrf
  @if($submitMethod === 'PUT')
    @method('PUT')
  @endif
  
  <x-form.card :submit_label="$submitLabel" :cancel_route="route('admin.hero-races.index')">
    <x-slot:header>
      <h2>{{ __('hero_races.form_title') }}</h2>
    </x-slot:header>
    
    <x-form.multilingual-input
      name="name"
      :label="__('hero_races.name')"
      :values="isset($heroRace) ? $heroRace->getTranslations('name') : []"
      required
    />
  </x-form.card>
</form>
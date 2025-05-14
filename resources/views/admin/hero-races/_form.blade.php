@php
  $submitRoute = isset($heroRace) 
    ? route('admin.hero-races.update', $heroRace) 
    : route('admin.hero-races.store');
  $submitMethod = isset($heroRace) ? 'PUT' : 'POST';
  $submitLabel = isset($heroRace) ? __('admin.update') : __('entities.hero_races.create');
@endphp

<form action="{{ $submitRoute }}" method="POST" class="form">
  @csrf
  @if($submitMethod === 'PUT')
    @method('PUT')
  @endif
  
  <x-form.card :submit_label="$submitLabel" :cancel_route="route('admin.hero-races.index')">
    <x-form.multilingual-input
      name="name"
      :label="__('entities.hero_races.name')"
      :values="isset($heroRace) ? $heroRace->getTranslations('name') : []"
      required
    />
  </x-form.card>
</form>
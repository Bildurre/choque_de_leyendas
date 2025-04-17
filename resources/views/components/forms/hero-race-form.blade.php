@props(['heroRace' => null, 'submitLabel' => 'Guardar', 'cancelRoute' => null])

<form 
  action="{{ $heroRace ? route('admin.hero-races.update', $heroRace) : route('admin.hero-races.store') }}" 
  method="POST" 
  class="hero-race-form"
>
  @csrf
  @if($heroRace) @method('PUT') @endif
  
  <x-form.card 
    :submit_label="$submitLabel"
    :cancel_route="$cancelRoute"
  >
    <x-form.field 
      name="name" 
      label="Nombre de la Raza" 
      :value="$heroRace->name ?? ''" 
      required
      max="255" 
    />
  </x-form.card>
</form>
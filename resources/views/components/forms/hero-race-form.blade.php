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

    <div class="modifiers-section">
      
      <div class="form-row">
        @php
          $attributes = [
            'agility' => 'Agilidad',
            'mental' => 'Mental',
            'will' => 'Voluntad',
            'strength' => 'Fuerza',
            'armor' => 'Armadura'
          ];
        @endphp

        @foreach($attributes as $key => $label)
          <x-form.field
            name="{{ $key }}_modifier" 
            :label="$label" 
            :value="$heroRace->{$key . '_modifier'} ?? 0"
            type="number"
            min="-3" 
            max="3"
            required
          />
        @endforeach
      </div>

      <div class="modifiers-total-section">
        <p>Atributos modificados: <span id="modifiers-total">{{ 
          isset($heroRace) ? (
            abs($heroRace->agility_modifier) +
            abs($heroRace->mental_modifier) +
            abs($heroRace->will_modifier) +
            abs($heroRace->strength_modifier) +
            abs($heroRace->armor_modifier)
          ) : 0
        }}</span>/3</p>
        @error('modifiers')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>
  </x-form.card>
</form>
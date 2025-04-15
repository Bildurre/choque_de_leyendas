@props(['heroClass' => null, 'heroSuperclasses' => [], 'submitLabel' => 'Guardar', 'cancelRoute' => null])

<form 
  action="{{ $heroClass ? route('admin.hero-classes.update', $heroClass) : route('admin.hero-classes.store') }}" 
  method="POST" 
  class="hero-class-form"
>
  @csrf
  @if($heroClass) @method('PUT') @endif
  
  <x-form.card 
    :submit_label="$submitLabel"
    :cancel_route="$cancelRoute"
  >
    <div class="form-row">
      <x-form.field 
        name="name" 
        label="Nombre de la Clase" 
        :value="$heroClass->name ?? ''" 
        :required="true"
        max="255"
      />

      <x-form.select 
        name="hero_superclass_id"
        label="Superclase"
        :value="$heroClass->hero_superclass_id ?? ''"
        :required="true"
        :options="$heroSuperclasses->pluck('name', 'id')->toArray()"
      />
    </div>

    <x-form.wysiwyg 
      name="passive" 
      label="Habilidad Pasiva" 
      :value="$heroClass->passive ?? ''"
      :required="true"
    />
  </div>

  <div class="form-section">
    <div class="attribute-modifiers-section">   
      @php
        $attributes = [
          'agility' => 'Agilidad',
          'mental' => 'Mental',
          'will' => 'Voluntad', 
          'strength' => 'Fuerza',
          'armor' => 'Armadura'
        ];
      @endphp

      <div class="form-row">
        @foreach($attributes as $key => $label)
          <x-form.field
            name="{{ $key }}_modifier" 
            label="{{ $label }}" 
            :value="$heroClass->{$key . '_modifier'} ?? 0"
            type="number"
            min="-1" 
            max="1"
            required
          />
        @endforeach
      </div>

      <div class="modifiers-total-section">
        <p>Atributos modificados: <span id="modifiers-total">{{ 
          isset($heroClass) ? (
            abs($heroClass->agility_modifier) +
            abs($heroClass->mental_modifier) +
            abs($heroClass->will_modifier) +
            abs($heroClass->strength_modifier) +
            abs($heroClass->armor_modifier)
          ) : 0
        }}</span>/3</p>
        @error('modifiers')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>
  </x-form.card>
</form>
@props(['configuration' => null, 'submitLabel' => 'Guardar Configuración'])

<form action="{{ route('admin.hero-attributes.update') }}" method="POST" class="hero-attributes-form">
  @csrf
  @method('PUT')
  
  <x-form.card :submit_label="$submitLabel">
    <div class="form-row">
      @php
        $attributes = [
          'base_agility' => 'Agilidad',
          'base_mental' => 'Mente',
          'base_will' => 'Voluntad',
          'base_strength' => 'Fuerza',
          'base_armor' => 'Armadura'
        ];
      @endphp

      @foreach($attributes as $key => $label)
        <x-form.field  
          :name="$key" 
          :label="$label . ' Base'" 
          type="number"
          :value="$configuration->$key ?? 3" 
          required 
          min="0" 
        />
      @endforeach
    </div>

    <div class="form-row">
      <x-form.field 
        name="total_points" 
        label="Puntos Totales" 
        type="number"
        :value="$configuration->total_points ?? 45" 
        required
        min="1" 
        help="El número de puntos totales que hay para distribuir"
      />

      <x-form.field 
        name="base_life" 
        label="Vida" 
        :value="$configuration->total_points ?? 45" 
        disabled
        help="Vida = Total - ΣAtributos"
      />
    </div>
  </x-form.card>
</form>
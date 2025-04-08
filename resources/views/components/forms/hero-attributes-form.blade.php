@props(['configuration' => null, 'submitLabel' => 'Guardar Configuración'])

<form action="{{ route('admin.hero-attributes.update') }}" method="POST" class="hero-attributes-form">
  @csrf
  @method('PUT')
  
  <x-form-card :submit_label="$submitLabel">
    <div class="form-section">
      <div class="entities-grid">
        @php
          $attributes = [
            'base_agility' => 'Agility',
            'base_mental' => 'Mental',
            'base_will' => 'Will',
            'base_strength' => 'Strength',
            'base_armor' => 'Armor'
          ];
        @endphp

        @foreach($attributes as $key => $label)
          <x-form.field 
            :name="$key" 
            :label="$label . ' Base'" 
            type="number"
            :value="$configuration->$key ?? 3" 
            :required="true" 
            min="0" 
          />
        @endforeach
      </div>

      <x-form.field 
        name="total_points" 
        label="Total Points" 
        type="number"
        :value="$configuration->total_points ?? 45" 
        :required="true" 
        min="1" 
        help="The maximum number of additional points that can be distributed in hero creation."
        class="total-points-group"
      />

      @error('base_points')
        <div class="alert alert-danger">
          {{ $message }}
        </div>
      @enderror
    </div>
  </x-form-card>
</form>
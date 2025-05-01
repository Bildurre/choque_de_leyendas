
<form 
  action="{{ route('admin.hero-attributes-configurations.update') }}" 
  method="POST" 
  class="form hero-attributes-form"
>
  @csrf
  @method('PUT')

  <x-form.card :submit_label="'Guardar Configuración'">
    <h3>Límites de Valores de Atributos</h3>
    <div class="form-row">
      <x-form.field  
        name="min_attribute_value" 
        label="Valor Mínimo de Atributo" 
        type="number"
        :value="$configuration->min_attribute_value" 
        required 
        min="1" 
      />
      
      <x-form.field  
        name="max_attribute_value" 
        label="Valor Máximo de Atributo" 
        type="number"
        :value="$configuration->max_attribute_value" 
        required 
        min="1" 
      />
    </div>
    
    <h3>Límites de Valores Totales</h3>
    <div class="form-row">
      <x-form.field  
        name="min_total_attributes" 
        label="Suma Mínima de Atributos" 
        type="number"
        :value="$configuration->min_total_attributes" 
        required 
        min="5" 
      />
      
      <x-form.field  
        name="max_total_attributes" 
        label="Suma Máxima de Atributos" 
        type="number"
        :value="$configuration->max_total_attributes" 
        required 
        min="5" 
      />
    </div>
    
    <h3>Multiplicadores para Cálculo de Salud</h3>
    <div class="form-row">
      <x-form.field  
        name="agility_multiplier" 
        label="Multiplicador de Agilidad" 
        type="number"
        :value="$configuration->agility_multiplier" 
        required 
      />
      
      <x-form.field  
        name="mental_multiplier" 
        label="Multiplicador de Mental" 
        type="number"
        :value="$configuration->mental_multiplier" 
        required 
      />
      
      <x-form.field  
        name="will_multiplier" 
        label="Multiplicador de Voluntad" 
        type="number"
        :value="$configuration->will_multiplier" 
        required 
      />
    </div>
    
    <div class="form-row">
      <x-form.field  
        name="strength_multiplier" 
        label="Multiplicador de Fuerza" 
        type="number"
        :value="$configuration->strength_multiplier" 
        required 
      />
      
      <x-form.field  
        name="armor_multiplier" 
        label="Multiplicador de Armadura" 
        type="number"
        :value="$configuration->armor_multiplier" 
        required 
      />
      
      <x-form.field  
        name="total_health_base" 
        label="Base de Salud Total" 
        type="number"
        :value="$configuration->total_health_base" 
        required 
        min="1" 
        help="Valor base para calcular la salud del héroe"
      />
    </div>
    
    <div class="formula-preview">
      <h3>Vista previa de la fórmula:</h3>
      <p>Salud = {{ $configuration->total_health_base }} 
        {{ $configuration->agility_multiplier >= 0 ? '+' : '' }}{{ $configuration->agility_multiplier }} × Agilidad 
        {{ $configuration->mental_multiplier >= 0 ? '+' : '' }}{{ $configuration->mental_multiplier }} × Mental 
        {{ $configuration->will_multiplier >= 0 ? '+' : '' }}{{ $configuration->will_multiplier }} × Voluntad 
        {{ $configuration->strength_multiplier >= 0 ? '+' : '' }}{{ $configuration->strength_multiplier }} × Fuerza 
        {{ $configuration->armor_multiplier >= 0 ? '+' : '' }}{{ $configuration->armor_multiplier }} × Armadura
      </p>
    </div>
  </x-form.card>

</form>
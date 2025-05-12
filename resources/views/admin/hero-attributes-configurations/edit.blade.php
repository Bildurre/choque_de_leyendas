<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('hero_attributes.config') }}</h1>
  </div>
  
<div class="page-content">
  <form action="{{ route('admin.hero-attributes-configurations.update') }}" method="POST" class="form">
  @csrf
  @method('PUT')

    <x-form.card :submit_label="__('admin.update')">
      <div class="form-grid">
        <x-form.input
          type="number"
          name="min_attribute_value"
          :label="__('hero_attributes.min_attribute_value')"
          :value="$configuration->min_attribute_value"
          required
          min="1"
          max="3"
        />

        <x-form.input
          type="number"
          name="max_attribute_value"
          :label="__('hero_attributes.max_attribute_value')"
          :value="$configuration->max_attribute_value"
          required
          min="3"
          max="10"
        />

        <x-form.input
          type="number"
          name="min_total_attributes"
          :label="__('hero_attributes.min_total_attributes')"
          :value="$configuration->min_total_attributes"
          required
          min="5"
          max="20"
        />

        <x-form.input
          type="number"
          name="max_total_attributes"
          :label="__('hero_attributes.max_total_attributes')"
          :value="$configuration->max_total_attributes"
          required
          min="10"
          max="50"
        />
      </div>


      <div class="form-grid">
        <x-form.input
          type="number"
          name="total_health_base"
          :label="__('hero_attributes.total_health_base')"
          :value="$configuration->total_health_base"
          required
          min="10"
          max="100"
        />

        <x-form.input
          type="number"
          name="agility_multiplier"
          :label="__('hero_attributes.agility_multiplier')"
          :value="$configuration->agility_multiplier"
          required
          min="-5"
          max="5"
        />

        <x-form.input
          type="number"
          name="mental_multiplier"
          :label="__('hero_attributes.mental_multiplier')"
          :value="$configuration->mental_multiplier"
          required
          min="-5"
          max="5"
        />

        <x-form.input
          type="number"
          name="will_multiplier"
          :label="__('hero_attributes.will_multiplier')"
          :value="$configuration->will_multiplier"
          required
          min="-5"
          max="5"
        />

        <x-form.input
          type="number"
          name="strength_multiplier"
          :label="__('hero_attributes.strength_multiplier')"
          :value="$configuration->strength_multiplier"
          required
          min="-5"
          max="5"
        />

        <x-form.input
          type="number"
          name="armor_multiplier"
          :label="__('hero_attributes.armor_multiplier')"
          :value="$configuration->armor_multiplier"
          required
          min="-5"
          max="5"
        />
      </div>
    </x-form.card>
  </form>
</div>
  
  @push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.querySelector('form');
      const inputs = form.querySelectorAll('input[type="number"]');
      
      // Validation for max_attribute_value and min_attribute_value
      const minAttributeInput = form.querySelector('input[name="min_attribute_value"]');
      const maxAttributeInput = form.querySelector('input[name="max_attribute_value"]');
      
      function validateAttributeRange() {
        const minValue = parseInt(minAttributeInput.value, 10);
        const maxValue = parseInt(maxAttributeInput.value, 10);
        
        if (maxValue < minValue) {
          maxAttributeInput.setCustomValidity('El valor máximo debe ser mayor o igual que el valor mínimo');
        } else {
          maxAttributeInput.setCustomValidity('');
        }
      }
      
      minAttributeInput.addEventListener('input', validateAttributeRange);
      maxAttributeInput.addEventListener('input', validateAttributeRange);
      
      // Validation for max_total_attributes and min_total_attributes
      const minTotalInput = form.querySelector('input[name="min_total_attributes"]');
      const maxTotalInput = form.querySelector('input[name="max_total_attributes"]');
      
      function validateTotalRange() {
        const minValue = parseInt(minTotalInput.value, 10);
        const maxValue = parseInt(maxTotalInput.value, 10);
        
        if (maxValue < minValue) {
          maxTotalInput.setCustomValidity('El total máximo debe ser mayor o igual que el total mínimo');
        } else {
          maxTotalInput.setCustomValidity('');
        }
      }
      
      minTotalInput.addEventListener('input', validateTotalRange);
      maxTotalInput.addEventListener('input', validateTotalRange);
    });
  </script>
  @endpush
</x-admin-layout>
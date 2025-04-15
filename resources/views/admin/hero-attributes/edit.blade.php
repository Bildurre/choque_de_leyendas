<x-admin-layout
  title='Hero Attribute Configuration'
  headerTitle='Hero Attribute Configuration'
  containerTitle='Hero Attribute Configuration'
  subtitle="Configure base attributes and total points for hero creation"
>

  <div class="configuration-info">
    <div>
      Base Attributes Total
      {{ 
        $configuration->base_agility + 
        $configuration->base_mental + 
        $configuration->base_will + 
        $configuration->base_strength + 
        $configuration->base_armor 
      }}
    </div>
    <div>
      Total Available Points
      {{ $configuration->total_points }}
    </div>
    <div>
      Max Attribute Points
      {{ 
        $configuration->total_points - 
        ($configuration->base_agility + 
        $configuration->base_mental + 
        $configuration->base_will + 
        $configuration->base_strength + 
        $configuration->base_armor)
      }}
    </div>
  </div>

  <x-forms.hero-attributes-form
    :configuration="$configuration"
    :submitLabel="'Guardar Cambios'"
   />

</x-admin-layout>
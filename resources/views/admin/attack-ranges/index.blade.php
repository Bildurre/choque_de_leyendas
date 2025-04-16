<x-admin-layout
  title='Rangos de Ataque'
  headerTitle='Gestión de Rangos'
  containerTitle='Rangos de Ataque'
  subtitle='Gestión de rangos para los ataques y habilidades'
  :createRoute="route('admin.attack-ranges.create')"
>

  <x-entities-grid 
    empty_message="No hay rangos de ataque disponibles"
    :createRoute="route('admin.attack-ranges.create')"
    createLabel="Crear el primer rango"
    >
    @foreach($attackRanges as $range)
      <x-cards.attack-range-card 
        :range="$range"
        :editRoute="route('admin.attack-ranges.edit', $range)"
        :deleteRoute="route('admin.attack-ranges.destroy', $range)"
      />
    @endforeach
  </x-entities-grid>

</x-admin-layout>
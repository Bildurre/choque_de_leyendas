<x-admin-layout
  title='Tipos de Equipo'
  headerTitle='Gestión de Tipos de Equipo'
  containerTitle='Tipos de Equipo'
  subtitle='Gestión de tipos de armas y armaduras para el juego'
  :createRoute="route('admin.equipment-types.create')"
>

  <x-entities-grid 
    empty_message="No hay tipos de equipo disponibles"
    :createRoute="route('admin.equipment-types.create')"
    createLabel="Crear el primer tipo de equipo"
  >
    @foreach($equipmentTypes as $equipmentType)
      <x-cards.equipment-type-card 
        :equipmentType="$equipmentType"
        :editRoute="route('admin.equipment-types.edit', $equipmentType)"
        :deleteRoute="route('admin.equipment-types.destroy', $equipmentType)"
      />
    @endforeach
  </x-entities-grid>

</x-admin-layout>
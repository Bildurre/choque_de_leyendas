<x-admin-layout
  title='Subtipos de Ataque'
  headerTitle='Gestión de Subtipos'
  containerTitle='Subtipos de Ataque'
  subtitle='Gestión de subtipos para categorizar ataques y habilidades'
  :createRoute="route('admin.attack-subtypes.create')"
>

  <x-entities-grid 
    empty_message="No hay subtipos de ataque disponibles"
    :createRoute="route('admin.attack-subtypes.create')"
    createLabel="Crear el primer subtipo"
  >
    @foreach($attackSubtypes as $subtype)
      <x-cards.attack-subtype-card 
        :subtype="$subtype"
        :editRoute="route('admin.attack-subtypes.edit', $subtype)"
        :deleteRoute="route('admin.attack-subtypes.destroy', $subtype)"
      />
    @endforeach
  </x-entities-grid>

</x-admin-layout>
<x-admin-layout
  title='Tipos de Ataque'
  headerTitle='Gestión de Tipos'
  containerTitle='Tipos de Ataque'
  subtitle='Gestión de tipos principales para categorizar ataques y habilidades'
  :createRoute="route('admin.attack-types.create')"
  createLabel='+ Nueva'
>

  <x-entities-grid 
    empty_message="No hay tipos de ataque disponibles"
    :create_route="route('admin.attack-types.create')"
    create_label="Crear el primer tipo"
  >
    @foreach($attackTypes as $type)
      <x-cards.attack-type-card 
        :type="$type"
        :editRoute="route('admin.attack-types.edit', $type)"
        :deleteRoute="route('admin.attack-types.destroy', $type)"
      />
    @endforeach
  </x-entities-grid>

</x-admin-layout>
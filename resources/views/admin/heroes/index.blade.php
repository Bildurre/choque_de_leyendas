<x-admin-layout
  title='Héroes'
  headerTitle='Gestión de Héroes'
  containerTitle='Héroes'
  subtitle='Gestión de héroes para el juego'
  :createRoute="route('admin.heroes.create')"
>

  <x-entities-grid 
    empty_message="No hay héroes disponibles"
    :createRoute="route('admin.heroes.create')"
    createLabel="Crear el primer héroe"
  >
    @foreach($heroes as $hero)
      <x-cards.hero-card 
        :hero="$hero"
        :showRoute="route('admin.heroes.show', $hero)"
        :editRoute="route('admin.heroes.edit', $hero)"
        :deleteRoute="route('admin.heroes.destroy', $hero)"
      />
    @endforeach
  </x-entities-grid>

</x-admin-layout>
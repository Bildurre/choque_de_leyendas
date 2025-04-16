<x-admin-layout
  title='Razas de Héroes'
  headerTitle='Gestión de Razas'
  containerTitle='Razas de Héroes'
  subtitle='Gestión de razas para la creación de héroes'
  :createRoute="route('admin.hero-races.create')"
>

  <x-entities-grid 
    empty_message="No hay razas de héroes disponibles"
    :createRoute="route('admin.hero-races.create')"
    createLabel="Crear la primera raza"
  >
  @foreach($heroRaces as $heroRace)
    <x-cards.hero-race-card 
      :heroRace="$heroRace"
      :editRoute="route('admin.hero-races.edit', $heroRace)"
      :deleteRoute="route('admin.hero-races.destroy', $heroRace)"
    />
  @endforeach
  </x-entities-grid>

</x-admin-layout>
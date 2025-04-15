<x-admin-layout
  title='Facciones'
  headerTitle='Gestión de Facciones'
  containerTitle='Facciones'
  subtitle='Gestión de facciones del juego'
  :createRoute="route('admin.factions.create')"
  createLabel='+ Nueva'
>

  <x-entities-grid 
    empty_message="No hay facciones disponibles"
    :createRoute="route('admin.factions.create')"
    createLabel="Crear la primera facción"
  >
    @foreach($factions as $faction)
      <x-cards.faction-card 
        :faction="$faction"
        :showRoute="route('admin.factions.show', $faction)"
        :editRoute="route('admin.factions.edit', $faction)"
        :deleteRoute="route('admin.factions.destroy', $faction)"
      />
    @endforeach
  </x-entities-grid>

</x-admin-layout>
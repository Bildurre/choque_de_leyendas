<x-admin-layout
  title='Habilidades de Héroes'
  headerTitle='Gestión de Habilidades'
  containerTitle='Habilidades de Héroes'
  subtitle='Gestión de habilidades para los héroes'
  :createRoute="route('admin.hero-abilities.create')"
>

  
  {{ $heroAbilities->links() }}

  <x-entities-grid 
    empty_message="No hay habilidades disponibles"
    :createRoute="route('admin.hero-abilities.create')"
    createLabel="Crear la primera habilidad"
  >
  @foreach($heroAbilities as $ability)
    <x-cards.hero-ability-card 
      :ability="$ability"
      :editRoute="route('admin.hero-abilities.edit', $ability)"
      :deleteRoute="route('admin.hero-abilities.destroy', $ability)"
    />
  @endforeach
  </x-entities-grid>

  {{ $heroAbilities->links() }}

</x-admin-layout>
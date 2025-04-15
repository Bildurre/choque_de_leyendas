<x-admin-layout
  title='Habilidades de Héroes'
  headerTitle='Gestión de Habilidades'
  containerTitle='Habilidades de Héroes'
  subtitle='Gestión de habilidades para los héroes'
  :createRoute="route('admin.hero-abilities.create')"
  createLabel='+ Nueva'
>

  <x-entities-grid 
    empty_message="No hay habilidades disponibles"
    :create_route="route('admin.hero-abilities.create')"
    create_label="Crear la primera habilidad"
  >
  @foreach($heroAbilities as $ability)
    <x-cards.ability-card 
      :ability="$ability"
      :editRoute="route('admin.hero-abilities.edit', $ability)"
      :deleteRoute="route('admin.hero-abilities.destroy', $ability)"
    />
  @endforeach
  </x-entities-grid>

  <div class="pagination-container">
    {{ $heroAbilities->links() }}
  </div>

</x-admin-layout>
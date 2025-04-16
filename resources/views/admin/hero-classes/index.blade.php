<x-admin-layout
  title='Clases de Héroes'
  headerTitle='Gestión de Clases'
  containerTitle='Clases de Héroes'
  subtitle='Gestión de clases para la creación de héroes'
  :createRoute="route('admin.hero-classes.create')"
>

  <x-entities-grid 
    empty_message="No hay clases de héroes disponibles"
    :createRoute="route('admin.hero-classes.create')"
    createLabel="Crear la primera clase"
  >
  @foreach($heroClasses as $heroClass)
    <x-cards.hero-class-card 
      :heroClass="$heroClass"
      :heroCount="$heroClass->heroCount ?? 0"
      :editRoute="route('admin.hero-classes.edit', $heroClass)"
      :deleteRoute="route('admin.hero-classes.destroy', $heroClass)"
    />
  @endforeach
  </x-entities-grid>

</x-admin-layout>
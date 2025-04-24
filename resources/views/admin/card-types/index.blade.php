<x-admin-layout
  title='Tipos de Carta'
  headerTitle='Gestión de Tipos de Carta'
  containerTitle='Tipos de Carta'
  subtitle='Gestión de tipos de carta para categorizar cartas en el juego'
  :createRoute="route('admin.card-types.create')"
>

  <x-entities-grid 
    empty_message="No hay tipos de carta disponibles"
    :createRoute="route('admin.card-types.create')"
    createLabel="Crear el primer tipo de carta"
  >
    @foreach($cardTypes as $cardType)
      <x-cards.card-type-card 
        :cardType="$cardType"
        :editRoute="route('admin.card-types.edit', $cardType)"
        :deleteRoute="route('admin.card-types.destroy', $cardType)"
      />
    @endforeach
  </x-entities-grid>

</x-admin-layout>
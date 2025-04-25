<x-admin-layout
  title='Cartas'
  headerTitle='Gestión de Cartas'
  containerTitle='Cartas'
  subtitle='Gestión de cartas para el juego'
  :createRoute="route('admin.cards.create')"
>

  <x-entities-grid 
    columns="true"
    empty_message="No hay cartas disponibles"
    :createRoute="route('admin.cards.create')"
    createLabel="Crear la primera carta"
  >
    @foreach($cards as $card)
      <x-cards.card-card 
        :card="$card"
        :showRoute="route('admin.cards.show', $card)"
        :editRoute="route('admin.cards.edit', $card)"
        :deleteRoute="route('admin.cards.destroy', $card)"
      />
    @endforeach
  </x-entities-grid>

  {{ $cards->links() }}

</x-admin-layout>
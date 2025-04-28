<x-admin-layout
  title='{{ __("cards.title") }}'
  headerTitle='{{ __("cards.management") }}'
  containerTitle='{{ __("cards.title") }}'
  subtitle='{{ __("cards.management_subtitle") }}'
  :createRoute="route('admin.cards.create')"
>

  <x-entities-grid 
    columns="true"
    :empty_message="__('cards.no_items')"
    :createRoute="route('admin.cards.create')"
    :createLabel="__('cards.create_first')"
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
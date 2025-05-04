<x-admin-layout
  title='{{ __("card_types.title") }}'
  headerTitle='{{ __("card_types.management") }}'
  containerTitle='{{ __("card_types.title") }}'
  subtitle='{{ __("card_types.management_subtitle") }}'
  :createRoute="route('admin.card-types.create')"
>

  <x-game.entities-grid 
    :empty_message="__('card_types.no_items')"
    :createRoute="route('admin.card-types.create')"
    :createLabel="__('card_types.create_first')"
  >
    @foreach($cardTypes as $cardType)
      <x-game.cards.card-type-card 
        :cardType="$cardType"
        :editRoute="route('admin.card-types.edit', $cardType)"
        :deleteRoute="route('admin.card-types.destroy', $cardType)"
      />
    @endforeach
  </x-game.entities-grid>

</x-admin-layout>
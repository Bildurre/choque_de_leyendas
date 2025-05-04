<x-admin-layout
  title='{{ __("attack_subtypes.title") }}'
  headerTitle='{{ __("attack_subtypes.management") }}'
  containerTitle='{{ __("attack_subtypes.title") }}'
  subtitle='{{ __("attack_subtypes.management_subtitle") }}'
  :createRoute="route('admin.attack-subtypes.create')"
>

  <x-game.entities-grid 
    :empty_message="__('attack_subtypes.no_items')"
    :createRoute="route('admin.attack-subtypes.create')"
    :createLabel="__('attack_subtypes.create_first')"
  >
    @foreach($attackSubtypes as $subtype)
      <x-game.cards.attack-subtype-card 
        :subtype="$subtype"
        :editRoute="route('admin.attack-subtypes.edit', $subtype)"
        :deleteRoute="route('admin.attack-subtypes.destroy', $subtype)"
      />
    @endforeach
  </x-game.entities-grid>

</x-admin-layout>
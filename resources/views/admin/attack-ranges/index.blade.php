<x-admin-layout
  title='{{ __("attack_ranges.title") }}'
  headerTitle='{{ __("attack_ranges.management") }}'
  containerTitle='{{ __("attack_ranges.title") }}'
  subtitle='{{ __("attack_ranges.management_subtitle") }}'
  :createRoute="route('admin.attack-ranges.create')"
>

  <x-game.entities-grid 
    :empty_message="__('attack_ranges.no_items')"
    :createRoute="route('admin.attack-ranges.create')"
    :createLabel="__('attack_ranges.create_first')"
    >
    @foreach($attackRanges as $range)
      <x-game.cards.attack-range-card 
        :range="$range"
        :editRoute="route('admin.attack-ranges.edit', $range)"
        :deleteRoute="route('admin.attack-ranges.destroy', $range)"
      />
    @endforeach
  </x-game.entities-grid>

</x-admin-layout>
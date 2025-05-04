<x-admin-layout
  title='{{ __("factions.title") }}'
  headerTitle='{{ __("factions.management") }}'
  containerTitle='{{ __("factions.title") }}'
  subtitle='{{ __("factions.management_subtitle") }}'
  :createRoute="route('admin.factions.create')"
>

  <x-game.entities-grid 
    empty_message="{{ __('factions.no_items') }}"
    :createRoute="route('admin.factions.create')"
    createLabel="{{ __('factions.create_first') }}"
  >
    @foreach($factions as $faction)
      <x-game.cards.faction-card 
        :faction="$faction"
        :showRoute="route('admin.factions.show', $faction)"
        :editRoute="route('admin.factions.edit', $faction)"
        :deleteRoute="route('admin.factions.destroy', $faction)"
      />
    @endforeach
  </x-game.entities-grid>

</x-admin-layout>
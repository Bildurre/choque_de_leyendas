<x-admin-layout
  title='{{ __("hero_races.title") }}'
  headerTitle='{{ __("hero_races.management") }}'
  containerTitle='{{ __("hero_races.title") }}'
  subtitle='{{ __("hero_races.management_subtitle") }}'
  :createRoute="route('admin.hero-races.create')"
>

  <x-game.entities-grid 
    empty_message="{{ __('hero_races.no_items') }}"
    :createRoute="route('admin.hero-races.create')"
    createLabel="{{ __('hero_races.create_first') }}"
  >
  @foreach($heroRaces as $heroRace)
    <x-game.cards.hero-race-card 
      :heroRace="$heroRace"
      :editRoute="route('admin.hero-races.edit', $heroRace)"
      :deleteRoute="route('admin.hero-races.destroy', $heroRace)"
    />
  @endforeach
  </x-game.entities-grid>

</x-admin-layout>
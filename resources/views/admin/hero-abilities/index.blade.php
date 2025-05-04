<x-admin-layout
  title='{{ __("hero_abilities.title") }}'
  headerTitle='{{ __("hero_abilities.management") }}'
  containerTitle='{{ __("hero_abilities.title") }}'
  subtitle='{{ __("hero_abilities.management_subtitle") }}'
  :createRoute="route('admin.hero-abilities.create')"
>

  {{ $heroAbilities->links() }}

  <x-game.entities-grid 
    empty_message="{{ __('hero_abilities.no_items') }}"
    :createRoute="route('admin.hero-abilities.create')"
    createLabel="{{ __('hero_abilities.create_first') }}"
  >
  @foreach($heroAbilities as $ability)
    <x-game.cards.hero-ability-card 
      :ability="$ability"
      :editRoute="route('admin.hero-abilities.edit', $ability)"
      :deleteRoute="route('admin.hero-abilities.destroy', $ability)"
    />
  @endforeach
  </x-game.entities-grid>

  {{ $heroAbilities->links() }}

</x-admin-layout>
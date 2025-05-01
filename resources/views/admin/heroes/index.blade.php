<x-admin-layout
  title='{{ __("heroes.title") }}'
  headerTitle='{{ __("heroes.management") }}'
  containerTitle='{{ __("heroes.title") }}'
  subtitle='{{ __("heroes.management_subtitle") }}'
  :createRoute="route('admin.heroes.create')"
>

  <x-entities-grid 
    empty_message="{{ __('heroes.no_items') }}"
    :createRoute="route('admin.heroes.create')"
    createLabel="{{ __('heroes.create_first') }}"
  >
    @foreach($heroes as $hero)
      <x-cards.hero-card 
        :hero="$hero"
        :showRoute="route('admin.heroes.show', $hero)"
        :editRoute="route('admin.heroes.edit', $hero)"
        :deleteRoute="route('admin.heroes.destroy', $hero)"
      />
    @endforeach
  </x-entities-grid>

</x-admin-layout>
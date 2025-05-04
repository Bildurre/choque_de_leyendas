<x-admin-layout
  title='{{ __("hero_superclasses.title") }}'
  headerTitle='{{ __("hero_superclasses.management") }}'
  containerTitle='{{ __("hero_superclasses.title") }}'
  subtitle='{{ __("hero_superclasses.management_subtitle") }}'
  :createRoute="route('admin.hero-superclasses.create')"
>

  <x-game.entities-grid 
    empty_message="{{ __('hero_superclasses.no_items') }}"
    :createRoute="route('admin.hero-superclasses.create')"
    createLabel="{{ __('hero_superclasses.create_first') }}"
  >
    @foreach($heroSuperclasses as $heroSuperclass)
      <x-game.cards.hero-superclass-card 
        :heroSuperclass="$heroSuperclass"
        :editRoute="route('admin.hero-superclasses.edit', $heroSuperclass)"
        :deleteRoute="route('admin.hero-superclasses.destroy', $heroSuperclass)"
      />
    @endforeach
  </x-game.entities-grid>

</x-admin-layout>
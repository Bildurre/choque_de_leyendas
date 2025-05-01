<x-admin-layout
  title='{{ __("hero_classes.title") }}'
  headerTitle='{{ __("hero_classes.management") }}'
  containerTitle='{{ __("hero_classes.title") }}'
  subtitle='{{ __("hero_classes.management_subtitle") }}'
  :createRoute="route('admin.hero-classes.create')"
>

  <x-entities-grid 
    empty_message="{{ __('hero_classes.no_items') }}"
    :createRoute="route('admin.hero-classes.create')"
    createLabel="{{ __('hero_classes.create_first') }}"
  >
  @foreach($heroClasses as $heroClass)
    <x-cards.hero-class-card 
      :heroClass="$heroClass"
      :heroCount="$heroClass->heroCount ?? 0"
      :editRoute="route('admin.hero-classes.edit', $heroClass)"
      :deleteRoute="route('admin.hero-classes.destroy', $heroClass)"
    />
  @endforeach
  </x-entities-grid>

</x-admin-layout>
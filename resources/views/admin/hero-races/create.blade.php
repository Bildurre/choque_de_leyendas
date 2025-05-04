<x-admin-layout
  title='{{ __("hero_races.create") }}'
  headerTitle='{{ __("hero_races.create") }}'
  containerTitle='{{ __("hero_races.title") }}'
  subtitle='{{ __("hero_races.create_subtitle") }}'
  :backRoute="route('admin.hero-races.index')"
>

  <x-admin.forms.hero-race-form 
    :submitLabel="__('common.actions.create_entity', ['entity' => __('hero_races.singular')])" 
    :cancelRoute="route('admin.hero-races.index')" 
  />

</x-admin-layout>
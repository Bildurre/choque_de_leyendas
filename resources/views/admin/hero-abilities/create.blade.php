<x-admin-layout
  title='{{ __("hero_abilities.new") }}'
  headerTitle='{{ __("hero_abilities.create") }}'
  containerTitle='{{ __("hero_abilities.title") }}'
  subtitle='{{ __("hero_abilities.create_subtitle") }}'
  :backRoute="route('admin.hero-abilities.index')"
>

  <x-admin.forms.hero-ability-form 
    :ranges="$ranges"
    :subtypes="$subtypes"
    :selectedHeroes="[]"
    :isDefault="false"
    :submitLabel="__('common.actions.create_entity', ['entity' => __('hero_abilities.singular')])" 
    :cancelRoute="route('admin.hero-abilities.index')" 
  />

</x-admin-layout>
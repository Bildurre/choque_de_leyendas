<x-admin-layout
  title='{{ __("hero_abilities.edit") }}'
  headerTitle='{{ __("hero_abilities.edit") }}'
  containerTitle='{{ __("hero_abilities.title") }}'
  subtitle="{{ __('hero_abilities.edit_subtitle_with_name', ['name' => $heroAbility->name]) }}"
  :createRoute="route('admin.hero-abilities.create')"
  :backRoute="route('admin.hero-abilities.index')"
>

  <x-forms.hero-ability-form 
    :heroAbility="$heroAbility"
    :ranges="$ranges"
    :subtypes="$subtypes"
    :submitLabel="__('common.actions.save_changes')" 
    :cancelRoute="route('admin.hero-abilities.index')" 
  />

</x-admin-layout>
<x-admin-layout
  title='{{ __("hero_classes.edit") }}'
  headerTitle='{{ __("hero_classes.edit") }}'
  containerTitle='{{ __("hero_classes.title") }}'
  subtitle="{{ __('hero_classes.edit_subtitle_with_name', ['name' => $heroClass->name]) }}"
  :createRoute="route('admin.hero-classes.create')"
  :backRoute="route('admin.hero-classes.index')"
>

  <x-admin.forms.hero-class-form 
    :heroClass="$heroClass"
    :heroSuperclasses="$heroSuperclasses"
    :submitLabel="__('common.actions.save_changes')" 
    :cancelRoute="route('admin.hero-classes.index')" 
  />

</x-admin-layout>
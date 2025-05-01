<x-admin-layout
  title='{{ __("hero_superclasses.edit") }}'
  headerTitle='{{ __("hero_superclasses.edit") }}'
  containerTitle='{{ __("hero_superclasses.title") }}'
  subtitle="{{ __('hero_superclasses.edit_subtitle_with_name', ['name' => $heroSuperclass->name]) }}"
  :createRoute="route('admin.hero-superclasses.create')"
  :backRoute="route('admin.hero-superclasses.index')"
>

  <x-forms.hero-superclass-form 
    :heroSuperclass="$heroSuperclass" 
    :submitLabel="__('common.actions.save_changes')" 
    :cancelRoute="route('admin.hero-superclasses.index')" 
  />

</x-admin-layout>
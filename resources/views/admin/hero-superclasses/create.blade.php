<x-admin-layout
  title='{{ __("hero_superclasses.new") }}'
  headerTitle='{{ __("hero_superclasses.create") }}'
  containerTitle='{{ __("hero_superclasses.title") }}'
  subtitle='{{ __("hero_superclasses.create_subtitle") }}'
  :backRoute="route('admin.hero-superclasses.index')"
>

  <x-admin.forms.hero-superclass-form 
    :submitLabel="__('common.actions.create_entity', ['entity' => __('hero_superclasses.singular')])" 
    :cancelRoute="route('admin.hero-superclasses.index')" 
  />

</x-admin-layout>
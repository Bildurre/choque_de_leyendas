<x-admin-layout
  title='{{ __("hero_classes.create") }}'
  headerTitle='{{ __("hero_classes.create") }}'
  containerTitle='{{ __("hero_classes.title") }}'
  subtitle='{{ __("hero_classes.create_subtitle") }}'
  :backRoute="route('admin.hero-classes.index')"
>

  <x-forms.hero-class-form 
    :heroSuperclasses="$heroSuperclasses"
    :submitLabel="__('common.actions.create_entity', ['entity' => __('hero_classes.singular')])" 
    :cancelRoute="route('admin.hero-classes.index')" 
  />

</x-admin-layout>
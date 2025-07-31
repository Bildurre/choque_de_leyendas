<x-admin-layout>
  <x-admin.page-header :title="__('entities.heroes.edit')">
    <x-slot:actions>
      <x-button-link
        :href="route('admin.heroes.index')"
        variant="primary"
        icon="arrow-left"
      >
        {{ __('admin.back_to_list') }}
      </x-button-link>
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">
    @include('admin.heroes._form', [
      'factions' => $factions,
      'heroRaces' => $heroRaces,
      'heroClasses' => $heroClasses,
      'heroAbilities' => $heroAbilities,
      'attributesConfig' => $attributesConfig
    ])
  </div>
</x-admin-layout>
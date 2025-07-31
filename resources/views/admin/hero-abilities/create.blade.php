<x-admin-layout>
  <x-admin.page-header :title="__('entities.hero_abilities.edit')">
    <x-slot:actions>
      <x-button-link
        :href="route('admin.hero-abilities.index')"
        variant="primary"
        icon="arrow-left"
      >
        {{ __('admin.back_to_list') }}
      </x-button-link>
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">
    @include('admin.hero-abilities._form', [
      'attackRanges' => $attackRanges,
      'attackSubtypes' => $attackSubtypes
    ])
  </div>
</x-admin-layout>
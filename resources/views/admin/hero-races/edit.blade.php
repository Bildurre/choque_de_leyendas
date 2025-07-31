<x-admin-layout>
  <x-admin.page-header :title="__('entities.hero_races.edit')">
    <x-slot:actions>
      <x-button-link
        :href="route('admin.hero-races.index')"
        variant="primary"
        icon="arrow-left"
      >
        {{ __('admin.back_to_list') }}
      </x-button-link>
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">
    @include('admin.hero-races._form', [
      'heroRace' => $heroRace
    ])
  </div>
</x-admin-layout>
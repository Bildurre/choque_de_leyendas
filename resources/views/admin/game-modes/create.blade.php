<x-admin-layout>
  <x-admin.page-header :title="__('entities.game_modes.edit')">
    <x-slot:actions>
      <x-button-link
        :href="route('admin.game-modes.index')"
        variant="primary"
        icon="arrow-left"
      >
        {{ __('admin.back_to_list') }}
      </x-button-link>
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">
    @include('admin.game-modes._form')
  </div>
</x-admin-layout>
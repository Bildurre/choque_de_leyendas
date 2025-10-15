<x-admin-layout>
  <x-admin.page-header :title="__('export.database_title')">
    {{-- <x-slot:actions>
      <x-button-link
        :href="route('admin.export.json')"
        variant="secondary"
        icon="file-json"
      >
        {{ __('export.json_exports') }}
      </x-button-link>
      <x-button-link
        :href="route('admin.dashboard')"
        variant="secondary"
        icon="arrow-left"
      >
        {{ __('admin.back_to_dashboard') }}
      </x-button-link>
    </x-slot:actions> --}}
  </x-admin.page-header>
  
  <div class="page-content">
    @include('admin.export._database')
  </div>
</x-admin-layout>
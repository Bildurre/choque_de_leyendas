<x-admin-layout>
  <x-admin.page-header :title="__('export.json_title')">
    {{-- <x-slot:actions>
      <x-button-link
        :href="route('admin.export.database')"
        variant="secondary"
        icon="database"
      >
        {{ __('export.database_exports') }}
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
    <x-preview-management.section :title="__('export.json_exports_section')">
      @include('admin.export._cards')
      @include('admin.export._heroes')
      @include('admin.export._counters')
      @include('admin.export._classes')
      @include('admin.export._factions')
    </x-preview-management.section>

    @if(count($exports) > 0)
      <x-preview-management.section :title="__('export.recent_exports')">
        @include('admin.export._list', ['exports' => $exports])
      </x-preview-management.section>
    @endif
  </div>
</x-admin-layout>
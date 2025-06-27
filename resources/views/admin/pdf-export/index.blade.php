<x-admin-layout>
  <x-admin.page-header :title="__('admin.pdf_export.title')">
    <x-slot:actions>
      <x-button-link 
        :href="route('admin.pdf-export.dynamic')" 
        icon="layers" 
        variant="primary"
      >
        {{ __('admin.pdf_export.dynamic_exports') }}
      </x-button-link>
      <x-button-link 
        :href="route('admin.pdf-export.other')" 
        icon="file-text" 
        variant="secondary"
      >
        {{ __('admin.pdf_export.other_exports') }}
      </x-button-link>
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">
    {{-- Statistics Section --}}
    <x-collapsible-section 
      id="pdf-statistics-section" 
      title="{{ __('admin.pdf_export.statistics') }}"
      :open="true"
    >
      @include('admin.pdf-export.partials.statistics', ['statistics' => $statistics])
    </x-collapsible-section>
    
    {{-- Recent PDFs Section --}}
    <x-collapsible-section 
      id="pdf-recent-section" 
      title="{{ __('admin.pdf_export.recent_pdfs') }}"
    >
      @include('admin.pdf-export.partials.recent-pdfs', ['recentPdfs' => $recentPdfs])
    </x-collapsible-section>
  </div>
</x-admin-layout>
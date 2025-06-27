<x-admin-layout>
  <x-admin.page-header :title="__('admin.pdf_export.dynamic_exports')">
    <x-slot:actions>
      <x-button-link 
        :href="route('admin.pdf-export.index')" 
        icon="arrow-left" 
        variant="secondary"
      >
        {{ __('admin.back') }}
      </x-button-link>
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">
    {{-- PDF Options --}}
    <x-collapsible-section 
      id="pdf-options-section" 
      title="{{ __('admin.pdf_export.generation_options') }}"
      :open="true"
    >
      @include('admin.pdf-export.partials.pdf-options')
    </x-collapsible-section>
    
    {{-- Factions Section --}}
    <x-collapsible-section 
      id="pdf-factions-section" 
      title="{{ __('admin.pdf_export.factions') }}"
    >
      @include('admin.pdf-export.partials.factions-list', ['factions' => $factions])
    </x-collapsible-section>
    
    {{-- Decks Section --}}
    <x-collapsible-section 
      id="pdf-decks-section" 
      title="{{ __('admin.pdf_export.decks') }}"
    >
      @include('admin.pdf-export.partials.decks-list', ['decks' => $decks])
    </x-collapsible-section>
  </div>
  
  <script src="{{ asset('js/admin-pdf-export.js') }}" type="module"></script>
</x-admin-layout>
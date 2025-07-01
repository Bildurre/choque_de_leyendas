<x-admin-layout>
  <div class="page-header">
    <div class="page-header__content">
      <h1 class="page-title">{{ __('pdf.export_management') }}</h1>
      
      <div class="page-header__actions">
        <form action="{{ route('admin.pdf-export.cleanup') }}" method="POST" class="inline">
          @csrf
          <x-button
            type="submit"
            variant="warning"
            icon="trash"
            onclick="return confirm('{{ __('pdf.confirm_cleanup') }}')"
          >
            {{ __('pdf.cleanup_temporary') }}
          </x-button>
        </form>
      </div>
    </div>
  </div>
  
  <div class="page-content">
    <x-tabs>
      <x-slot:header>
        <x-tab-item 
          id="factions" 
          :active="$activeTab === 'factions'" 
          :href="route('admin.pdf-export.index', ['tab' => 'factions'])"
          icon="heroes"
        >
          {{ __('entities.factions.plural') }}
        </x-tab-item>
        
        <x-tab-item 
          id="decks" 
          :active="$activeTab === 'decks'" 
          :href="route('admin.pdf-export.index', ['tab' => 'decks'])"
          icon="box"
        >
          {{ __('entities.faction_decks.plural') }}
        </x-tab-item>
        
        <x-tab-item 
          id="others" 
          :active="$activeTab === 'others'" 
          :href="route('admin.pdf-export.index', ['tab' => 'others'])"
          icon="layers"
        >
          {{ __('pdf.others') }}
        </x-tab-item>
      </x-slot:header>
      
      <x-slot:content>
        @if($activeTab === 'factions')
          @include('admin.pdf-export._factions', [
            'factions' => $factions,
            'existingPdfs' => $existingPdfs
          ])
        @elseif($activeTab === 'decks')
          @include('admin.pdf-export._decks', [
            'decks' => $decks,
            'existingPdfs' => $existingPdfs
          ])
        @elseif($activeTab === 'others')
          @include('admin.pdf-export._others', [
            'customExports' => $customExports,
            'existingPdfs' => $existingPdfs
          ])
        @endif
      </x-slot:content>
    </x-tabs>
  </div>
</x-admin-layout>
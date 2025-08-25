<x-admin-layout>
  <x-admin.page-header :title="__('pdf.plural')">
    <x-slot:actions>
      <form action="{{ route('admin.pdf-export.cleanup') }}" method="POST" class="inline">
        @csrf
        <x-button
          type="submit"
          variant="danger"
          icon="trash"
          onclick="return confirm('{{ __('pdf.confirm_cleanup') }}')"
        >
          {{ __('pdf.cleanup_temporary') }}
        </x-button>
      </form>
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">
    {{-- Simple navigation links instead of JavaScript tabs --}}
    <div class="tabs">
      <div class="tabs__header">
        <a href="{{ route('admin.pdf-export.index', ['tab' => 'factions']) }}" 
           class="tabs__item {{ $activeTab === 'factions' ? 'tabs__item--active' : '' }}">
          <x-icon name="heroes" size="sm" class="tabs__icon" />
          <span class="tabs__text">{{ __('entities.factions.plural') }}</span>
        </a>
        
        <a href="{{ route('admin.pdf-export.index', ['tab' => 'decks']) }}" 
           class="tabs__item {{ $activeTab === 'decks' ? 'tabs__item--active' : '' }}">
          <x-icon name="decks" size="sm" class="tabs__icon" />
          <span class="tabs__text">{{ __('entities.faction_decks.plural') }}</span>
        </a>
        
        <a href="{{ route('admin.pdf-export.index', ['tab' => 'pages']) }}" 
           class="tabs__item {{ $activeTab === 'pages' ? 'tabs__item--active' : '' }}">
          <x-icon name="layers" size="sm" class="tabs__icon" />
          <span class="tabs__text">{{ __('pages.plural') }}</span>
        </a>
        
        <a href="{{ route('admin.pdf-export.index', ['tab' => 'others']) }}" 
           class="tabs__item {{ $activeTab === 'others' ? 'tabs__item--active' : '' }}">
          <x-icon name="pdf" size="sm" class="tabs__icon" />
          <span class="tabs__text">{{ __('pdf.other') }}</span>
        </a>
      </div>
      
      <div class="tabs__content">
        {{-- Show content based on active tab --}}
        @if($activeTab === 'factions')
          @include('admin.pdf-export._factions-list', [
            'factions' => $factions,
            'existingPdfs' => $existingPdfs['faction'] ?? []
          ])
        @elseif($activeTab === 'decks')
          @include('admin.pdf-export._decks-list', [
            'decks' => $decks,
            'existingPdfs' => $existingPdfs['deck'] ?? []
          ])
        @elseif($activeTab === 'pages')
          @include('admin.pdf-export._pages-list', [
            'pages' => $pages,
            'existingPdfs' => $existingPdfs['page'] ?? []
          ])
        @else
          @include('admin.pdf-export._others-list', [
            'existingPdfs' => $existingPdfs['others'] ?? []
          ])
        @endif
      </div>
    </div>
  </div>
</x-admin-layout>
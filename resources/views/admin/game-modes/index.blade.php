<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('game_modes.plural') }}</h1>
  </div>
  
  <div class="page-content">
    <x-entity.list 
      :create-route="!$trashed ? route('admin.game-modes.create') : null"
      :create-label="__('game_modes.create')"
      :items="$gameModes"
      :withTabs="true"
      :trashed="$trashed"
      :activeCount="$activeCount ?? null"
      :trashedCount="$trashedCount ?? null"
      baseRoute="admin.game-modes.index"
    >
      @foreach($gameModes as $gameMode)
        <x-entity.list-card 
          :title="$gameMode->name"
          :edit-route="!$trashed ? route('admin.game-modes.edit', $gameMode) : null"
          :delete-route="$trashed 
            ? route('admin.game-modes.force-delete', $gameMode->id) 
            : route('admin.game-modes.destroy', $gameMode)"
          :restore-route="$trashed ? route('admin.game-modes.restore', $gameMode->id) : null"
          :confirm-message="$trashed 
            ? __('game_modes.confirm_force_delete') 
            : __('game_modes.confirm_delete')"
        >
          <x-slot:badges>
            <x-badge variant="primary">
              {{ __('game_modes.faction_decks_count', ['count' => $gameMode->faction_decks_count]) }}
            </x-badge>
            
            @if($trashed)
              <x-badge variant="danger">
                {{ __('admin.deleted_at', ['date' => $gameMode->deleted_at->format('d/m/Y H:i')]) }}
              </x-badge>
            @endif
          </x-slot:badges>
          
          @if($gameMode->description)
            <div class="game-mode-details">
              <div class="game-mode-details__description">
                {{ strip_tags($gameMode->description) }}
              </div>
            </div>
          @endif
        </x-entity.list-card>
      @endforeach
      
      @if(method_exists($gameModes, 'links'))
        <x-slot:pagination>
          {{ $gameModes->appends(['trashed' => $trashed ? 1 : null])->links() }}
        </x-slot:pagination>
      @endif
    </x-entity.list>
  </div>
</x-admin-layout>
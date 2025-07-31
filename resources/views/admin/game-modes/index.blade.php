<x-admin-layout>
  <x-admin.page-header :title="__('entities.game_modes.plural')">
    <x-slot:actions>
      @if(!$trashed)
        <x-button-link
          :href="route('admin.game-modes.create')"
          variant="primary"
          icon="plus"
        >
          {{ __('entities.game_modes.create') }}
        </x-button-link>
      @endif
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">
    <x-entity.list 
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
            ? __('entities.game_modes.confirm_force_delete') 
            : __('entities.game_modes.confirm_delete')"
        >
          <x-slot:badges>
            <x-badge variant="primary">
              {{ __('entities.game_modes.faction_decks_count', ['count' => $gameMode->faction_decks_count]) }}
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
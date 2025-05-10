<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('card_types.plural') }}</h1>
  </div>
  
  <div class="page-content">
    <x-tabs>
      <x-slot:header>
        <x-tab-item 
          id="active" 
          :active="!$trashed" 
          :href="route('admin.card-types.index')"
          icon="list"
          :count="$activeCount ?? null"
        >
          {{ __('admin.active_items') }}
        </x-tab-item>
        
        <x-tab-item 
          id="trashed" 
          :active="$trashed" 
          :href="route('admin.card-types.index', ['trashed' => 1])"
          icon="trash"
          :count="$trashedCount ?? null"
        >
          {{ __('admin.trashed_items') }}
        </x-tab-item>
      </x-slot:header>
      
      <x-slot:content>
        <x-entity.list 
          title="{{ $trashed ? __('card_types.trashed') : __('card_types.plural') }}"
          :create-route="!$trashed ? route('admin.card-types.create') : null"
          :create-label="__('card_types.create')"
          :items="$cardTypes"
        >
          @foreach($cardTypes as $cardType)
            @if($trashed)
              <x-entity.list-card 
                :title="$cardType->name"
                :delete-route="route('admin.card-types.force-delete', $cardType->id)"
                :confirm-message="__('card_types.confirm_force_delete')"
              >
                <x-slot:actions>
                  <form action="{{ route('admin.card-types.restore', $cardType->id) }}" method="POST" class="action-button-form">
                    @csrf
                    <button 
                      type="submit" 
                      class="action-button action-button--restore"
                      title="{{ __('admin.restore') }}"
                    >
                      <x-icon name="refresh" size="sm" class="action-button__icon" />
                    </button>
                  </form>
                </x-slot:actions>
                
                <x-slot:badges>
                  @if($cardType->cards_count > 0)
                    <x-badge variant="info">
                      {{ __('card_types.cards_count', ['count' => $cardType->cards_count]) }}
                    </x-badge>
                  @endif
                  
                  @if($cardType->heroSuperclass)
                    <x-badge variant="primary">
                      {{ __('hero_superclasses.singular') }}: {{ $cardType->heroSuperclass->name }}
                    </x-badge>
                  @endif
                  
                  <x-badge variant="danger">
                    {{ __('admin.deleted_at', ['date' => $cardType->deleted_at->format('d/m/Y H:i')]) }}
                  </x-badge>
                </x-slot:badges>
              </x-entity.list-card>
            @else
              <x-entity.list-card 
                :title="$cardType->name"
                :edit-route="route('admin.card-types.edit', $cardType)"
                :delete-route="route('admin.card-types.destroy', $cardType)"
                :confirm-message="__('card_types.confirm_delete')"
              >
                <x-slot:badges>
                  @if($cardType->cards_count > 0)
                    <x-badge variant="info">
                      {{ __('card_types.cards_count', ['count' => $cardType->cards_count]) }}
                    </x-badge>
                  @endif
                  
                  @if($cardType->heroSuperclass)
                    <x-badge variant="primary">
                      {{ __('hero_superclasses.singular') }}: {{ $cardType->heroSuperclass->name }}
                    </x-badge>
                  @endif
                </x-slot:badges>
              </x-entity.list-card>
            @endif
          @endforeach
          
          @if(method_exists($cardTypes, 'links'))
            <x-slot:pagination>
              {{ $cardTypes->appends(['trashed' => $trashed ? 1 : null])->links() }}
            </x-slot:pagination>
          @endif
        </x-entity.list>
      </x-slot:content>
    </x-tabs>
  </div>
</x-admin-layout>
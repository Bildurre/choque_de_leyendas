<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('entities.cards.plural') }}</h1>
  </div>
  
  <div class="page-content">
    <x-entity.list 
      :create-route="!$trashed ? route('admin.cards.create') : null"
      :create-label="__('entities.cards.create')"
      :items="$cards"
      :withTabs="true"
      :trashed="$trashed"
      :activeCount="$activeCount ?? null"
      :trashedCount="$trashedCount ?? null"
      baseRoute="admin.cards.index"
    >
      @foreach($cards as $card)
        <x-entity.list-card 
          :title="$card->name"
          :view-route="!$trashed ? route('admin.cards.show', $card) : null"
          :edit-route="!$trashed ? route('admin.cards.edit', $card) : null"
          :delete-route="$trashed 
            ? route('admin.cards.force-delete', $card->id) 
            : route('admin.cards.destroy', $card)"
          :restore-route="$trashed ? route('admin.cards.restore', $card->id) : null"
          :confirm-message="$trashed 
            ? __('entities.cards.confirm_force_delete') 
            : __('entities.cards.confirm_delete')"
        >
          <x-slot:badges>
            <x-badge variant="primary">
              {{ $card->cardType->name }}
            </x-badge>
          
            <x-badge 
              :variant="$card->faction->text_is_dark ? 'light' : 'dark'" 
              style="background-color: {{ $card->faction->color }};"
            >
              {{ $card->faction->name }}
            </x-badge>
            
            @if($card->equipmentType)
              <x-badge variant="info">
                {{ $card->equipmentType->name }} 
                @if($card->hands)
                  ({{ $card->hands }} {{ trans_choice('entities.cards.hands_count', $card->hands) }})
                @endif
              </x-badge>
            @endif
            
            @if($card->attackSubtype)
              <x-badge variant="{{ $card->attackSubtype->type === 'physical' ? 'warning' : 'success' }}">
                {{ $card->attackSubtype->name }}
                @if($card->area)
                  ({{ __('entities.cards.area') }})
                @endif
              </x-badge>
            @endif
            
            @if($card->cost)
              <div class="badge-with-icons">
                <span class="badge-with-icons__cost">
                  <x-cost-display :cost="$card->cost" />
                </span>
              </div>
            @endif
            
            @if($trashed)
              <x-badge variant="danger">
                {{ __('admin.deleted_at', ['date' => $card->deleted_at->format('d/m/Y H:i')]) }}
              </x-badge>
            @endif
          </x-slot:badges>
          
          <div class="card-details">
            <div class="card-details__content">
              @if($card->restriction)
                <div class="card-details__section">
                  <h4 class="card-details__label">{{ __('entities.cards.restriction') }}:</h4>
                  <div class="card-details__text">{{ strip_tags($card->restriction) }}</div>
                </div>
              @endif

              <div class="card-details__section">
                <div class="card-details__text">{{ strip_tags($card->effect) }}</div>
              </div>
            </div>
          </div>
        </x-entity.list-card>
      @endforeach
      
      @if(method_exists($cards, 'links'))
        <x-slot:pagination>
          {{ $cards->appends(['trashed' => $trashed ? 1 : null])->links() }}
        </x-slot:pagination>
      @endif
    </x-entity.list>
  </div>
</x-admin-layout>
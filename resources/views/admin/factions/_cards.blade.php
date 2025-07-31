<x-entity.list
  :items="$cards"
  :showHeader="false"
  emptyMessage="{{ __('entities.factions.no_cards') }}"
>
  @foreach($cards as $card)
    <x-entity.list-card 
      :title="$card->name"
      :view-route="route('admin.cards.show', $card)"
      :edit-route="route('admin.cards.edit', $card)"
    >
      <x-slot:badges>    
        @if($card->isPublished())
          <x-badge variant="success">{{ __('admin.published') }}</x-badge>
        @else
          <x-badge variant="warning">{{ __('admin.draft') }}</x-badge>
        @endif
      </x-slot:badges>
      
      <div class="card-details">
        <x-previews.preview-image :entity="$card" type="card"/>
      </div>
    </x-entity.list-card>
  @endforeach

  <x-slot:pagination>
    {{ $cards->appends(['tab' => 'cards'])->links() }}
  </x-slot:pagination>
</x-entity.list>
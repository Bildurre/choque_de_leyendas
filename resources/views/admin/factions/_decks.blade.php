<x-entity.list
  :items="$decks"
  :showHeader="false"
  emptyMessage="{{ __('entities.factions.no_decks') }}"
>
  @foreach($decks as $deck)
    <x-entity.list-card 
      :title="$deck->name"
      :view-route="route('admin.faction-decks.show', $deck)"
      :edit-route="route('admin.faction-decks.edit', $deck)"
    >
      <x-slot:badges>
        @if($deck->isPublished())
          <x-badge variant="success">{{ __('admin.published') }}</x-badge>
        @else
          <x-badge variant="warning">{{ __('admin.draft') }}</x-badge>
        @endif
      </x-slot:badges>
      
      <div class="deck-details">
        <x-previews.preview-image :entity="$deck" type="deck"/>
      </div>
    </x-entity.list-card>
  @endforeach

  <x-slot:pagination>
    {{ $decks->appends(['tab' => 'decks'])->links() }}
  </x-slot:pagination>
</x-entity.list>
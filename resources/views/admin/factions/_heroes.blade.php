<x-entity.list
  :items="$heroes"
  :showHeader="false"
  emptyMessage="{{ __('entities.factions.no_heroes') }}"
>
  @foreach($heroes as $hero)
    <x-entity.list-card 
      :title="$hero->name"
      :view-route="route('admin.heroes.show', $hero)"
      :edit-route="route('admin.heroes.edit', $hero)"
    >
      <x-slot:badges>
        @if($hero->isPublished())
          <x-badge variant="success">{{ __('admin.published') }}</x-badge>
        @else
          <x-badge variant="warning">{{ __('admin.draft') }}</x-badge>
        @endif
      </x-slot:badges>
      
      <div class="hero-details">
        <x-previews.preview-image :entity="$hero" type="hero"/>
      </div>
    </x-entity.list-card>
  @endforeach

  <x-slot:pagination>
    {{ $heroes->appends(['tab' => 'heroes'])->links() }}
  </x-slot:pagination>
</x-entity.list>
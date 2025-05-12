<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('attack_ranges.plural') }}</h1>
  </div>
  
  <div class="page-content">
    <x-entity.list 
      :create-route="!$trashed ? route('admin.attack-ranges.create') : null"
      :create-label="__('attack_ranges.create')"
      :items="$attackRanges"
      :withTabs="true"
      :trashed="$trashed"
      :activeCount="$activeCount ?? null"
      :trashedCount="$trashedCount ?? null"
      baseRoute="admin.attack-ranges.index"
    >
      @foreach($attackRanges as $attackRange)
        <x-entity.list-card 
          :title="$attackRange->name"
          :edit-route="!$trashed ? route('admin.attack-ranges.edit', $attackRange) : null"
          :delete-route="$trashed 
            ? route('admin.attack-ranges.force-delete', $attackRange->id) 
            : route('admin.attack-ranges.destroy', $attackRange)"
          :restore-route="$trashed ? route('admin.attack-ranges.restore', $attackRange->id) : null"
          :confirm-message="$trashed 
            ? __('attack_ranges.confirm_force_delete') 
            : __('attack_ranges.confirm_delete')"
        >
          <x-slot:badges>
              <x-badge variant="info">
                {{ __('attack_ranges.hero_abilities_count', ['count' => $attackRange->hero_abilities_count]) }}
              </x-badge>
            
              <x-badge variant="primary">
                {{ __('attack_ranges.cards_count', ['count' => $attackRange->cards_count]) }}
              </x-badge>
            
            @if($trashed)
              <x-badge variant="danger">
                {{ __('admin.deleted_at', ['date' => $attackRange->deleted_at->format('d/m/Y H:i')]) }}
              </x-badge>
            @endif
          </x-slot:badges>
          
          @if($attackRange->icon)
            <div class="attack-range-icon">
              <img src="{{ $attackRange->getIconUrl() }}" alt="{{ $attackRange->name }}" class="attack-range-icon__image">
            </div>
          @endif
        </x-entity.list-card>
      @endforeach
      
      @if(method_exists($attackRanges, 'links'))
        <x-slot:pagination>
          {{ $attackRanges->appends(['trashed' => $trashed ? 1 : null])->links() }}
        </x-slot:pagination>
      @endif
    </x-entity.list>
  </div>
</x-admin-layout>
<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('entities.attack_subtypes.plural') }}</h1>
  </div>
  
  <div class="page-content">
    <x-entity.list 
      :create-route="!$trashed ? route('admin.attack-subtypes.create') : null"
      :create-label="__('entities.attack_subtypes.create')"
      :items="$attackSubtypes"
      :withTabs="true"
      :trashed="$trashed"
      :activeCount="$activeCount ?? null"
      :trashedCount="$trashedCount ?? null"
      baseRoute="admin.attack-subtypes.index"
    >
      @foreach($attackSubtypes as $attackSubtype)
        <x-entity.list-card 
          :title="$attackSubtype->name"
          :edit-route="!$trashed ? route('admin.attack-subtypes.edit', $attackSubtype) : null"
          :delete-route="$trashed 
            ? route('admin.attack-subtypes.force-delete', $attackSubtype->id) 
            : route('admin.attack-subtypes.destroy', $attackSubtype)"
          :restore-route="$trashed ? route('admin.attack-subtypes.restore', $attackSubtype->id) : null"
          :confirm-message="$trashed 
            ? __('entities.attack_subtypes.confirm_force_delete') 
            : __('entities.attack_subtypes.confirm_delete')"
        >
          <x-slot:badges>
            <x-badge variant="{{ $attackSubtype->type === 'physical' ? 'warning' : 'success' }}">
              {{ $attackSubtype->type_name }}
            </x-badge>
            
            <x-badge variant="info">
              {{ __('entities.attack_subtypes.hero_abilities_count', ['count' => $attackSubtype->hero_abilities_count]) }}
            </x-badge>
          
            <x-badge variant="primary">
              {{ __('entities.attack_subtypes.cards_count', ['count' => $attackSubtype->cards_count]) }}
            </x-badge>
            
            @if($trashed)
              <x-badge variant="danger">
                {{ __('admin.deleted_at', ['date' => $attackSubtype->deleted_at->format('d/m/Y H:i')]) }}
              </x-badge>
            @endif
          </x-slot:badges>
        </x-entity.list-card>
      @endforeach
      
      @if(method_exists($attackSubtypes, 'links'))
        <x-slot:pagination>
          {{ $attackSubtypes->appends(['trashed' => $trashed ? 1 : null])->links() }}
        </x-slot:pagination>
      @endif
    </x-entity.list>
  </div>
</x-admin-layout>
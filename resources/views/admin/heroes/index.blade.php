<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('entities.heroes.plural') }}</h1>
  </div>
  
  <div class="page-content">

    <x-filters.card 
      :model="$heroModel" 
      :request="$request" 
      :itemsCount="$heroes->count()"
      :totalCount="$totalCount"
      :filteredCount="$filteredCount"
    />

    <x-entity.list 
      :create-route="!$trashed ? route('admin.heroes.create') : null"
      :create-label="__('entities.heroes.create')"
      :items="$heroes"
      :withTabs="true"
      :trashed="$trashed"
      :activeCount="$activeCount ?? null"
      :trashedCount="$trashedCount ?? null"
      baseRoute="admin.heroes.index"
    >
      @foreach($heroes as $hero)
        <x-entity.list-card 
          :title="$hero->name"
          :view-route="!$trashed ? route('admin.heroes.show', $hero) : null"
          :edit-route="!$trashed ? route('admin.heroes.edit', $hero) : null"
          :delete-route="$trashed 
            ? route('admin.heroes.force-delete', $hero->id) 
            : route('admin.heroes.destroy', $hero)"
          :restore-route="$trashed ? route('admin.heroes.restore', $hero->id) : null"
          :toggle-published-route="!$trashed ? route('admin.heroes.toggle-published', $hero) : null"
          :is-published="$hero->isPublished()"
          :confirm-message="$trashed 
            ? __('entities.heroes.confirm_force_delete') 
            : __('entities.heroes.confirm_delete')"
        >
          <x-slot:badges>
            <x-badge 
              :variant="$hero->faction->text_is_dark ? 'light' : 'dark'" 
              style="background-color: {{ $hero->faction->color }};"
            >
              {{ $hero->faction->name }}
            </x-badge>
            
            <x-badge variant="info">
              {{ $hero->heroRace->name }}
            </x-badge>
            
            <x-badge variant="primary">
              {{ $hero->heroClass->name }}
            </x-badge>
            
            <x-badge variant="{{ $hero->gender === 'male' ? 'success' : 'warning' }}">
              {{ __('entities.heroes.genders.' . $hero->gender) }}
            </x-badge>
            
            @if($hero->isPublished())
              <x-badge variant="success">
                {{ __('admin.published') }}
              </x-badge>
            @else
              <x-badge variant="warning">
                {{ __('admin.draft') }}
              </x-badge>
            @endif
            
            @if($trashed)
              <x-badge variant="danger">
                {{ __('admin.deleted_at', ['date' => $hero->deleted_at->format('d/m/Y H:i')]) }}
              </x-badge>
            @endif
          </x-slot:badges>
          
          <div class="hero-details">
            <x-previews.preview-image :entity="$hero" type="hero"/>
          </div>
        </x-entity.list-card>
      @endforeach
      
      @if(method_exists($heroes, 'links'))
        <x-slot:pagination>
          {{ $heroes->appends(['trashed' => $trashed ? 1 : null])->links() }}
        </x-slot:pagination>
      @endif
    </x-entity.list>
  </div>
</x-admin-layout>
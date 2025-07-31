<x-admin-layout>
  <x-admin.page-header :title="__('entities.hero_superclasses.plural')">
    <x-slot:actions>
      @if(!$trashed)
        <x-button-link
          :href="route('admin.hero-superclasses.create')"
          variant="primary"
          icon="plus"
        >
          {{ __('entities.hero_superclasses.create') }}
        </x-button-link>
      @endif
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">

    <x-filters.card 
      :model="$heroSuperclassModel" 
      :request="$request" 
      :itemsCount="$heroSuperclasses->count()"
      :totalCount="$totalCount"
      :filteredCount="$filteredCount"
    />

    <x-entity.list 
      :items="$heroSuperclasses"
      :withTabs="true"
      :trashed="$trashed"
      :activeCount="$activeCount ?? null"
      :trashedCount="$trashedCount ?? null"
      baseRoute="admin.hero-superclasses.index"
    >
      @foreach($heroSuperclasses as $heroSuperclass)
        <x-entity.list-card 
          :title="$heroSuperclass->name"
          :edit-route="!$trashed ? route('admin.hero-superclasses.edit', $heroSuperclass) : null"
          :delete-route="$trashed 
            ? route('admin.hero-superclasses.force-delete', $heroSuperclass->id) 
            : route('admin.hero-superclasses.destroy', $heroSuperclass)"
          :restore-route="$trashed ? route('admin.hero-superclasses.restore', $heroSuperclass->id) : null"
          :confirm-message="$trashed 
            ? __('entities.hero_superclasses.confirm_force_delete') 
            : __('entities.hero_superclasses.confirm_delete')"
        >
          <x-slot:badges>
            <x-badge variant="info">
              {{ __('entities.hero_superclasses.classes_count', ['count' => $heroSuperclass->hero_classes_count]) }}
            </x-badge>
            
            @if($trashed)
              <x-badge variant="danger">
                {{ __('admin.deleted_at', ['date' => $heroSuperclass->deleted_at->format('d/m/Y H:i')]) }}
              </x-badge>
            @endif
          </x-slot:badges>
        </x-entity.list-card>
      @endforeach
      
      @if(method_exists($heroSuperclasses, 'links'))
        <x-slot:pagination>
          {{ $heroSuperclasses->appends(['trashed' => $trashed ? 1 : null])->links() }}
        </x-slot:pagination>
      @endif
    </x-entity.list>
  </div>
</x-admin-layout>
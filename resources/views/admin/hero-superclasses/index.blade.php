<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('hero_superclasses.plural') }}</h1>
  </div>
  
  <div class="page-content">
    <x-entity.list 
      :create-route="!$trashed ? route('admin.hero-superclasses.create') : null"
      :create-label="__('hero_superclasses.create')"
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
            ? __('hero_superclasses.confirm_force_delete') 
            : __('hero_superclasses.confirm_delete')"
        >
          <x-slot:badges>
            <x-badge variant="info">
              {{ __('hero_superclasses.classes_count', ['count' => $heroSuperclass->hero_classes_count]) }}
            </x-badge>
            
            @if($trashed)
              <x-badge variant="danger">
                {{ __('admin.deleted_at', ['date' => $heroSuperclass->deleted_at->format('d/m/Y H:i')]) }}
              </x-badge>
            @endif
          </x-slot:badges>
          
          @if($heroSuperclass->icon)
            <div class="hero-superclass-icon">
              <img src="{{ $heroSuperclass->getIconUrl() }}" alt="{{ $heroSuperclass->name }}" class="hero-superclass-icon__image">
            </div>
          @endif
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
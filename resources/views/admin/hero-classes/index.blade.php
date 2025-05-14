<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('entities.hero_classes.plural') }}</h1>
  </div>
  
  <div class="page-content">
    <x-entity.list 
      :create-route="!$trashed ? route('admin.hero-classes.create') : null"
      :create-label="__('entities.hero_classes.create')"
      :items="$heroClasses"
      :withTabs="true"
      :trashed="$trashed"
      :activeCount="$activeCount ?? null"
      :trashedCount="$trashedCount ?? null"
      baseRoute="admin.hero-classes.index"
    >
      @foreach($heroClasses as $heroClass)
        <x-entity.list-card 
          :title="$heroClass->name"
          :edit-route="!$trashed ? route('admin.hero-classes.edit', $heroClass) : null"
          :delete-route="$trashed 
            ? route('admin.hero-classes.force-delete', $heroClass->id) 
            : route('admin.hero-classes.destroy', $heroClass)"
          :restore-route="$trashed ? route('admin.hero-classes.restore', $heroClass->id) : null"
          :confirm-message="$trashed 
            ? __('entities.hero_classes.confirm_force_delete') 
            : __('entities.hero_classes.confirm_delete')"
        >
          <x-slot:badges>
            <x-badge variant="primary">
              {{ $heroClass->heroSuperclass->name }}
            </x-badge>
            
            <x-badge variant="info">
              {{ __('entities.hero_classes.heroes_count', ['count' => $heroClass->heroes_count]) }}
            </x-badge>
            
            @if($trashed)
              <x-badge variant="danger">
                {{ __('admin.deleted_at', ['date' => $heroClass->deleted_at->format('d/m/Y H:i')]) }}
              </x-badge>
            @endif
          </x-slot:badges>
          
          @if($heroClass->passive)
            <div class="hero-class-passive">
              <div class="hero-class-passive__text">
                {!! $heroClass->passive !!}
              </div>
            </div>
          @endif
        </x-entity.list-card>
      @endforeach
      
      @if(method_exists($heroClasses, 'links'))
        <x-slot:pagination>
          {{ $heroClasses->appends(['trashed' => $trashed ? 1 : null])->links() }}
        </x-slot:pagination>
      @endif
    </x-entity.list>
  </div>
</x-admin-layout>
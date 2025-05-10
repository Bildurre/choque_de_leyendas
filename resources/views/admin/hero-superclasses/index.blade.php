<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('hero_superclasses.plural') }}</h1>
  </div>
  
  <div class="page-content">
    <x-tabs>
      <x-slot:header>
        <x-tab-item 
          id="active" 
          :active="!$trashed" 
          :href="route('admin.hero-superclasses.index')"
          icon="list"
          :count="$activeCount ?? null"
        >
          {{ __('admin.active_items') }}
        </x-tab-item>
        
        <x-tab-item 
          id="trashed" 
          :active="$trashed" 
          :href="route('admin.hero-superclasses.index', ['trashed' => 1])"
          icon="trash"
          :count="$trashedCount ?? null"
        >
          {{ __('admin.trashed_items') }}
        </x-tab-item>
      </x-slot:header>
      
      <x-slot:content>
        <x-entity.list 
          title="{{ $trashed ? __('hero_superclasses.trashed') : __('hero_superclasses.plural') }}"
          :create-route="!$trashed ? route('admin.hero-superclasses.create') : null"
          :create-label="__('hero_superclasses.create')"
          :items="$heroSuperclasses"
        >
          @foreach($heroSuperclasses as $heroSuperclass)
            @if($trashed)
              <x-entity.list-card 
                :title="$heroSuperclass->name"
                :delete-route="route('admin.hero-superclasses.force-delete', $heroSuperclass->id)"
                :confirm-message="__('hero_superclasses.confirm_force_delete')"
              >
                <x-slot:actions>
                  <form action="{{ route('admin.hero-superclasses.restore', $heroSuperclass->id) }}" method="POST" class="action-button-form">
                    @csrf
                    <button 
                      type="submit" 
                      class="action-button action-button--restore"
                      title="{{ __('admin.restore') }}"
                    >
                      <x-icon name="refresh" size="sm" class="action-button__icon" />
                    </button>
                  </form>
                </x-slot:actions>
                
                <x-slot:badges>
                  @if($heroSuperclass->hero_classes_count > 0)
                    <x-badge variant="info">
                      {{ __('hero_superclasses.classes_count', ['count' => $heroSuperclass->hero_classes_count]) }}
                    </x-badge>
                  @endif
                  
                  @if($heroSuperclass->card_type_count > 0)
                    <x-badge variant="primary">
                      {{ __('hero_superclasses.card_type') }}
                    </x-badge>
                  @endif
                  
                  <x-badge variant="danger">
                    {{ __('admin.deleted_at', ['date' => $heroSuperclass->deleted_at->format('d/m/Y H:i')]) }}
                  </x-badge>
                </x-slot:badges>
                
                @if($heroSuperclass->icon)
                  <div class="hero-superclass-icon">
                    <img src="{{ $heroSuperclass->getIconUrl() }}" alt="{{ $heroSuperclass->name }}" class="hero-superclass-icon__image">
                  </div>
                @endif
              </x-entity.list-card>
            @else
              <x-entity.list-card 
                :title="$heroSuperclass->name"
                :edit-route="route('admin.hero-superclasses.edit', $heroSuperclass)"
                :delete-route="route('admin.hero-superclasses.destroy', $heroSuperclass)"
                :confirm-message="__('hero_superclasses.confirm_delete')"
              >
                <x-slot:badges>
                  @if($heroSuperclass->hero_classes_count > 0)
                    <x-badge variant="info">
                      {{ __('hero_superclasses.classes_count', ['count' => $heroSuperclass->hero_classes_count]) }}
                    </x-badge>
                  @endif
                  
                  @if($heroSuperclass->card_type_count > 0)
                    <x-badge variant="primary">
                      {{ __('hero_superclasses.card_type') }}
                    </x-badge>
                  @endif
                </x-slot:badges>
                
                @if($heroSuperclass->icon)
                  <div class="hero-superclass-icon">
                    <img src="{{ $heroSuperclass->getIconUrl() }}" alt="{{ $heroSuperclass->name }}" class="hero-superclass-icon__image">
                  </div>
                @endif
              </x-entity.list-card>
            @endif
          @endforeach
          
          @if(method_exists($heroSuperclasses, 'links'))
            <x-slot:pagination>
              {{ $heroSuperclasses->appends(['trashed' => $trashed ? 1 : null])->links() }}
            </x-slot:pagination>
          @endif
        </x-entity.list>
      </x-slot:content>
    </x-tabs>
  </div>
</x-admin-layout>
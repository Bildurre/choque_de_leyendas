<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('hero_classes.plural') }}</h1>
  </div>
  
  <div class="page-content">
    <x-tabs>
      <x-slot:header>
        <x-tab-item 
          id="active" 
          :active="!$trashed" 
          :href="route('admin.hero-classes.index')"
          icon="list"
        >
          {{ __('admin.active_items') }}
        </x-tab-item>
        
        <x-tab-item 
          id="trashed" 
          :active="$trashed" 
          :href="route('admin.hero-classes.index', ['trashed' => 1])"
          icon="trash"
        >
          {{ __('admin.trashed_items') }}
        </x-tab-item>
      </x-slot:header>
      
      <x-slot:content>
        <x-entity.list 
          title="{{ $trashed ? __('hero_classes.trashed') : __('hero_classes.plural') }}"
          :create-route="!$trashed ? route('admin.hero-classes.create') : null"
          :create-label="__('hero_classes.create')"
          :items="$heroClasses"
        >
          @foreach($heroClasses as $heroClass)
            @if($trashed)
              <x-entity.list-card 
                :title="$heroClass->name"
                :delete-route="route('admin.hero-classes.force-delete', $heroClass->id)"
                :confirm-message="__('hero_classes.confirm_force_delete')"
              >
                <x-slot:actions>
                  <form action="{{ route('admin.hero-classes.restore', $heroClass->id) }}" method="POST" class="action-button-form">
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
                  <x-badge variant="primary">
                    {{ $heroClass->heroSuperclass->name }}
                  </x-badge>
                  
                  @if($heroClass->heroes_count > 0)
                    <x-badge variant="info">
                      {{ __('hero_classes.heroes_count', ['count' => $heroClass->heroes_count]) }}
                    </x-badge>
                  @endif
                  
                  <x-badge variant="danger">
                    {{ __('admin.deleted_at', ['date' => $heroClass->deleted_at->format('d/m/Y H:i')]) }}
                  </x-badge>
                </x-slot:badges>
                
                @if($heroClass->passive)
                  <div class="hero-class-passive">
                    <div class="hero-class-passive__text">
                      {!! $heroClass->passive !!}
                    </div>
                  </div>
                @endif
              </x-entity.list-card>
            @else
              <x-entity.list-card 
                :title="$heroClass->name"
                :edit-route="route('admin.hero-classes.edit', $heroClass)"
                :delete-route="route('admin.hero-classes.destroy', $heroClass)"
                :confirm-message="__('hero_classes.confirm_delete')"
              >
                <x-slot:badges>
                  <x-badge variant="primary">
                    {{ $heroClass->heroSuperclass->name }}
                  </x-badge>
                  
                  @if($heroClass->heroes_count > 0)
                    <x-badge variant="info">
                      {{ __('hero_classes.heroes_count', ['count' => $heroClass->heroes_count]) }}
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
            @endif
          @endforeach
          
          @if(method_exists($heroClasses, 'links'))
            <x-slot:pagination>
              {{ $heroClasses->appends(['trashed' => $trashed ? 1 : null])->links() }}
            </x-slot:pagination>
          @endif
        </x-entity.list>
      </x-slot:content>
    </x-tabs>
  </div>
</x-admin-layout>
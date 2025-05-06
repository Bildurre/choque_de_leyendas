<x-admin-layout>



  <x-entity.list 
    title="{{ __('hero_classes.plural') }}"
    :create-route="route('admin.hero-classes.create')"
    :create-label="__('hero_classes.create')"
    :items="$heroClasses"
  >
    @foreach($heroClasses as $heroClass)
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
    @endforeach
    
    @if(method_exists($heroClasses, 'links'))
      <x-slot:pagination>
        {{ $heroClasses->links() }}
      </x-slot:pagination>
    @endif
  </x-entity.list>
</x-admin-layout>
<x-admin-layout>
  <x-admin.page-header :title="__('entities.deck_attributes.configurations')">
    <x-slot:actions>
      <x-button-link
        :href="route('admin.deck-attributes-configurations.create')"
        variant="primary"
        icon="plus"
      >
        {{ __('entities.deck_attributes.create') }}
      </x-button-link>
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">
    <x-entity.list 
      :items="$configurations"
      :emptyMessage="__('entities.deck_attributes.no_configurations')"
    >
      @foreach($configurations as $configuration)
        <x-entity.list-card 
          :title="$configuration->gameMode->name"
          :edit-route="route('admin.deck-attributes-configurations.edit', $configuration)"
          :delete-route="route('admin.deck-attributes-configurations.destroy', $configuration)"
          :confirm-message="__('entities.deck_attributes.confirm_delete')"
        >
          <div class="deck-attributes-details">
            <div class="deck-attributes-details__item">
              <span class="deck-attributes-details__label">{{ __('entities.deck_attributes.min_cards') }}:</span>
              <span class="deck-attributes-details__value">{{ $configuration->min_cards }}</span>
            </div>
            
            <div class="deck-attributes-details__item">
              <span class="deck-attributes-details__label">{{ __('entities.deck_attributes.max_cards') }}:</span>
              <span class="deck-attributes-details__value">{{ $configuration->max_cards }}</span>
            </div>
            
            <div class="deck-attributes-details__item">
              <span class="deck-attributes-details__label">{{ __('entities.deck_attributes.max_copies_per_card') }}:</span>
              <span class="deck-attributes-details__value">{{ $configuration->max_copies_per_card }}</span>
            </div>

            <div class="deck-attributes-details__item">
              <span class="deck-attributes-details__label">{{ __('entities.deck_attributes.required_heroes') }}:</span>
              <span class="deck-attributes-details__value">{{ $configuration->required_heroes }}</span>
            </div>
          </div>
        </x-entity.list-card>
      @endforeach
      
      @if(method_exists($configurations, 'links'))
        <x-slot:pagination>
          {{ $configurations->links() }}
        </x-slot:pagination>
      @endif
    </x-entity.list>
  </div>
</x-admin-layout>
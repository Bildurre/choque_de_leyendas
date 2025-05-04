<x-admin-layout
  title="{{ $card->name }}"
  headerTitle='{{ __("cards.show") }}'
  containerTitle="{{ $card->name }}"
  subtitle='{{ __("cards.show_subtitle") }}'
  :createRoute="route('admin.cards.create')"
  :backRoute="route('admin.cards.index')"
>

  <x-detail-card 
    :title="$card->name"
    :accentColor="$card->faction->color ?? '#3d3df5'"
    :model="$card"
    :editRoute="route('admin.cards.edit', $card)"
    :deleteRoute="route('admin.cards.destroy', $card)"
    confirmAttribute="name"
  >
    <x-slot:headerSlot>
      <x-previews.card-preview :card="$card" />
    </x-slot:headerSlot>

    <x-detail-section title="{{ __('cards.general_info') }}">
      <x-info-grid :columns="3">
        <x-info-grid-item label="{{ __('cards.faction') }}" :value="$card->faction->name ?? __('cards.no_faction')" />
        <x-info-grid-item label="{{ __('cards.type') }}" :value="$card->cardType->name ?? __('cards.not_assigned')" />
        
        @if($card->equipmentType)
          <x-info-grid-item label="{{ __('cards.equipment_type') }}" :value="$card->equipmentType->name" />
          
          @if($card->isWeapon())
            <x-info-grid-item label="{{ __('cards.hands') }}" :value="$card->hands" />
          @endif
        @endif
        
        @if($card->attackRange)
          <x-info-grid-item label="{{ __('cards.attack_range') }}" :value="$card->attackRange->name" />
        @endif
        
        @if($card->attackSubtype)
          <x-info-grid-item label="{{ __('cards.attack_subtype') }}" :value="$card->attackSubtype->name" />
          <x-info-grid-item label="{{ __('cards.attack_type') }}" :value="$card->attackSubtype->typeName" />
        @endif
        
        @if($card->area)
          <x-info-grid-item label="{{ __('cards.area') }}" :value="{{ __('common.yes') }}" />
        @endif
        
        @if($card->cost)
          <x-info-grid-item label="{{ __('cards.cost') }}">
            <x-game.cost-display :cost="$card->cost"/>
          </x-info-grid-item>
        @endif
        
        @if($card->heroAbility)
          <x-info-grid-item label="{{ __('cards.ability') }}" :value="$card->heroAbility->name" />
        @endif
      </x-info-grid>
    </x-detail-section>
    
    @if($card->lore_text)
      <x-detail-section title="{{ __('cards.lore') }}">
        <x-detail-text :content="$card->lore_text" />
      </x-detail-section>
    @endif
    
    @if($card->effect)
      <x-detail-section title="{{ __('cards.effect') }}">
        <x-detail-text :content="$card->effect" isHtml="true" />
      </x-detail-section>
    @endif
    
    @if($card->restriction)
      <x-detail-section title="{{ __('cards.restriction') }}">
        <x-detail-text :content="$card->restriction" isHtml="true" />
      </x-detail-section>
    @endif

    @if($card->image)
      <x-detail-section title="{{ __('cards.image') }}">
        <x-detail-image 
          :src="asset('storage/' . $card->image)" 
          :alt="$card->name" 
          size="lg"
        />
      </x-detail-section>
    @endif
  </x-detail-card>

</x-admin-layout>
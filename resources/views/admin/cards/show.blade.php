<x-admin-layout>
  <x-admin.page-header :title="$card->name">
    <x-slot:actionButtons>
      <x-action-button
        :href="route('admin.cards.edit', $card)"
        icon="edit"
        variant="edit"
        size="lg"
        :title="__('admin.edit')"
      />
    
      @if (!$card->trashed())
        <x-action-button
          :route="route('admin.cards.toggle-published', $card)"
          :icon="$card->is_published ? 'globe-slash' : 'globe'"
          :variant="$card->is_published ? 'unpublish' : 'publish'"
          size="lg"
          method="POST"
          :title="$card->is_published ? __('admin.unpublish') : __('admin.publish')"
        />
      @else
        <x-action-button
          :route="route('admin.cards.restore', $card->id)"
          icon="refresh"
          variant="restore"
          size="lg"
          method="POST"
          :title="__('admin.restore')"
        />
      @endif
    
      <x-action-button
        :route="$card->trashed()
            ? route('admin.cards.force-delete', $card->id) 
            : route('admin.cards.destroy', $card)"
        icon="trash"
        variant="delete"
        size="lg"
        method="DELETE"
        :confirm-message="$card->trashed()
            ? __('entities.cards.confirm_force_delete') 
            : __('entities.cards.confirm_delete')"
        :title="__('admin.delete')"
      />
    </x-slot:actionButtons>

    <x-slot:actions>
      <x-button-link
        :href="route('admin.cards.index')"
        variant="secondary"
        icon="arrow-left"
      >
        {{ __('admin.back_to_list') }}
      </x-button-link>
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">
    <div class="card-details-wrapper">
      <div class="info-blocks-grid">
        {{-- Card Preview --}}
        <div class="card-preview-section">
          <x-previews.preview-image :entity="$card" type="card"/>
        </div>

        <div class="card-preview-section">
          <x-previews.card :card="$card"/>
        </div>

        {{-- Basic Information --}}
        <x-entity-show.info-block title="entities.cards.basic_info">
          <x-entity-show.info-list>
            <x-entity-show.info-list-item 
              label="{{ __('entities.cards.name') }}"
              :value="$card->name" 
            />

            <x-entity-show.info-list-item label="{{ __('entities.factions.singular') }}">
              @if($card->faction)
                <x-entity-show.info-link :href="route('admin.factions.show', [$card->faction])">
                  {{ $card->faction->name }}
                </x-entity-show.info-link>
              @else
                {{ __('entities.cards.no_faction') }}
              @endif
            </x-entity-show.info-list-item>

            @if($card->is_unique)
              <x-entity-show.info-list-item 
                label="{{ __('entities.cards.is_unique') }}"
                :value="__('common.yes')" 
              />
            @endif
            
            <x-entity-show.info-list-item 
              label="{{ __('entities.card_types.singular') }}"
              :value="$card->cardType->name"
            />

            @if ($card->cardSubype)
              <x-entity-show.info-list-item 
              label="{{ __('entities.card_subtypes.singular') }}"
              :value="$card->cardSubype->name"
            />
            @endif

            @if($card->equipmentType)
              <x-entity-show.info-list-item 
                label="{{ __('entities.equipment_types.singular') }}"
                value="{{ __('entities.equipment_types.categories.' . $card->equipmentType->category) }}"
              />
              <x-entity-show.info-list-item 
                label="{{ __('entities.equipment_types.category') }}"
                :value="$card->equipmentType->name"
              />
              
              @if($card->hands)
                <x-entity-show.info-list-item 
                  label="{{ __('entities.cards.hands_required') }}"
                  :value="$card->hands == 1 ? __('entities.cards.one_hand') : __('entities.cards.two_hands')" 
                />
              @endif
            @endif
            
            @if($card->attackRange)
              <x-entity-show.info-list-item 
                label="{{ __('entities.attack_ranges.singular') }}"
                :value="$card->attackRange->name"
              />
            @endif

            @if($card->attack_type)
              <x-entity-show.info-list-item 
                :label="__('entities.attack_subtypes.damage_type')"
                value="{{ __('entities.cards.attack_types.' . $card->attack_type) }}"
              />
            @endif
            
            @if($card->attackSubtype)
              <x-entity-show.info-list-item 
                label="{{ __('entities.attack_subtypes.singular') }}"
                :value="$card->attackSubtype->name"
              />
            @endif
            
            @if($card->area)
              <x-entity-show.info-list-item 
                label="{{ __('entities.cards.is_area_attack') }}"
                :value="__('common.yes')" 
              />
            @endif
            
            @if($card->cost)
              <x-entity-show.info-list-item label="{{ __('entities.cards.cost') }}">
                <x-cost-display :cost="$card->cost" />
              </x-entity-show.info-list-item>
            @endif
            
            <x-entity-show.info-list-item label="{{ __('admin.status') }}">
              @if($card->isPublished())
                <x-badge variant="success">{{ __('admin.published') }}</x-badge>
              @else
                <x-badge variant="warning">{{ __('admin.draft') }}</x-badge>
              @endif
            </x-entity-show.info-list-item>

            @if($card->trashed())
              <x-entity-show.info-list-item 
                label="{{ __('admin.deleted_at') }}"
                :value="$card->deleted_at->format('d/m/Y H:i')" 
              />
            @endif
          </x-entity-show.info-list>
        </x-entity-show.info-block>

        {{-- Card Image --}}
        @if($card->hasImage())
          <x-entity-show.info-block title="entities.cards.image">
            <div class="card-image">
              <img src="{{ $card->getImageUrl() }}" alt="{{ $card->name }}">
            </div>
          </x-entity-show.info-block>
        @endif

        {{-- Effect --}}
        @if($card->effect || $card->restriction || $card->heroAbility)
          <x-entity-show.info-block title="entities.cards.effect">
            <div class="prose">
              {!! $card->restriction !!}
              {!! $card->effect !!}
            </div>

            @if($card->heroAbility)
              <x-entity-show.ability-card
                variant="active"
                :name="$card->heroAbility->name"
                :description="$card->heroAbility->description"
                :cost="$card->heroAbility->cost"
                :attack-range="$card->heroAbility->attackRange"
                :attack-type="$card->heroAbility->attack_type"
                :attack-subtype="$card->heroAbility->attackSubtype"
                :area="$card->heroAbility->area"
              />
            @endif
          </x-entity-show.info-block>
        @endif

        {{-- Lore Text --}}
        @if($card->lore_text)
          <x-entity-show.info-block title="entities.cards.lore_text">
            <div class="prose">
              {!! $card->lore_text !!}
            </div>
          </x-entity-show.info-block>
        @endif

        {{-- Epic Quote --}}
        @if($card->epic_quote)
          <x-entity-show.info-block title="entities.cards.epic_quote">
            <blockquote class="epic-quote">
              {!! $card->epic_quote !!}
            </blockquote>
          </x-entity-show.info-block>
        @endif

        {{-- Timestamps --}}
        <x-entity-show.info-block title="admin.timestamps">
          <x-entity-show.info-list>
            <x-entity-show.info-list-item 
              label="{{ __('admin.created_at') }}"
              :value="$card->created_at->format('d/m/Y H:i')" 
            />
            
            <x-entity-show.info-list-item 
              label="{{ __('admin.updated_at') }}"
              :value="$card->updated_at->format('d/m/Y H:i')" 
            />
          </x-entity-show.info-list>
        </x-entity-show.info-block>
      </div>
    </div>
  </div>
</x-admin-layout>
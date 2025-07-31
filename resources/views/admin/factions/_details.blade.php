<div class="tab-content">
  <div class="info-blocks-grid">
    {{-- Basic Information --}}
    <x-entity-show.info-block title="entities.factions.basic_info">
      <x-entity-show.info-list>
        <x-entity-show.info-list-item 
          label="{{ __('entities.factions.name') }}"
          :value="$faction->name" 
        />
        
        <x-entity-show.info-list-item 
          label="{{ __('entities.factions.color') }}"
        >
          <div class="color-preview" style="background-color: {{ $faction->color }}; width: 100px; height: 30px; border-radius: 4px; border: 1px solid #ccc;"></div>
        </x-entity-show.info-list-item>
        
        <x-entity-show.info-list-item 
          label="{{ __('entities.heroes.plural') }}"
          :value="$faction->heroes_count" 
        />
        
        <x-entity-show.info-list-item 
          label="{{ __('entities.cards.plural') }}"
          :value="$faction->cards_count" 
        />
        
        <x-entity-show.info-list-item 
          label="{{ __('entities.faction_decks.plural') }}"
          :value="$faction->faction_decks_count" 
        />
        
        <x-entity-show.info-list-item label="{{ __('admin.status') }}">
          @if($faction->isPublished())
            <x-badge variant="success">{{ __('admin.published') }}</x-badge>
          @else
            <x-badge variant="warning">{{ __('admin.draft') }}</x-badge>
          @endif
        </x-entity-show.info-list-item>

        @if($faction->trashed())
          <x-entity-show.info-list-item 
            label="{{ __('admin.deleted_at') }}"
            :value="$faction->deleted_at->format('d/m/Y H:i')" 
          />
        @endif
      </x-entity-show.info-list>
    </x-entity-show.info-block>

    {{-- Icon --}}
    @if($faction->hasImage())
      <x-entity-show.info-block title="entities.factions.icon">
        <div class="faction-icon">
          <img src="{{ $faction->getImageUrl() }}" alt="{{ $faction->name }}">
        </div>
      </x-entity-show.info-block>
    @endif

    {{-- Lore Text --}}
    @if($faction->lore_text)
      <x-entity-show.info-block title="entities.factions.lore_text">
        <div class="prose">
          {!! $faction->lore_text !!}
        </div>
      </x-entity-show.info-block>
    @endif

    {{-- Epic Quote --}}
    @if($faction->epic_quote)
      <x-entity-show.info-block title="entities.factions.epic_quote">
        <blockquote class="epic-quote">
          {!! $faction->epic_quote !!}
        </blockquote>
      </x-entity-show.info-block>
    @endif

    {{-- Timestamps --}}
    <x-entity-show.info-block title="admin.timestamps">
      <x-entity-show.info-list>
        <x-entity-show.info-list-item 
          label="{{ __('admin.created_at') }}"
          :value="$faction->created_at->format('d/m/Y H:i')" 
        />
        
        <x-entity-show.info-list-item 
          label="{{ __('admin.updated_at') }}"
          :value="$faction->updated_at->format('d/m/Y H:i')" 
        />
      </x-entity-show.info-list>
    </x-entity-show.info-block>
  </div>
</div>
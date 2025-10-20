<x-admin-layout>
  <x-admin.page-header :title="$hero->name">
    <x-slot:actionButtons>
      <x-action-button
        :href="route('admin.heroes.edit', $hero)"
        icon="edit"
        variant="edit"
        size="lg"
        :title="__('admin.edit')"
      />
    
      @if (!$hero->trashed())
        <x-action-button
          :route="route('admin.heroes.toggle-published', $hero)"
          :icon="$hero->is_published ? 'globe-slash' : 'globe'"
          :variant="$hero->is_published ? 'unpublish' : 'publish'"
          size="lg"
          method="POST"
          :title="$hero->is_published ? __('admin.unpublish') : __('admin.publish')"
        />
      @else
        <x-action-button
          :route="route('admin.heroes.restore', $hero->id)"
          icon="refresh"
          variant="restore"
          size="lg"
          method="POST"
          :title="__('admin.restore')"
        />
      @endif
    
      <x-action-button
        :route="$hero->trashed()
            ? route('admin.heroes.force-delete', $hero->id) 
            : route('admin.heroes.destroy', $hero)"
        icon="trash"
        variant="delete"
        size="lg"
        method="DELETE"
        :confirm-message="$hero->trashed()
            ? __('entities.heroes.confirm_force_delete') 
            : __('entities.heroes.confirm_delete')"
        :title="__('admin.delete')"
      />
    </x-slot:actionButtons>

    <x-slot:actions>
      <x-button-link
        :href="route('admin.heroes.index')"
        variant="secondary"
        icon="arrow-left"
      >
        {{ __('admin.back_to_list') }}
      </x-button-link>
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">
    <div class="hero-details-wrapper">
      {{-- Hero Information --}}
      <div class="info-blocks-grid">

        
        <div class="hero-preview-section">
          <x-previews.hero :hero="$hero" />
        </div>

        {{-- Hero Preview --}}
        <div class="hero-preview-section">
          <x-previews.preview-image :entity="$hero" type="hero"/>
        </div>
        {{-- Basic Information --}}
        <x-entity-show.info-block title="entities.heroes.basic_info">
          <x-entity-show.info-list>
            <x-entity-show.info-list-item 
              label="{{ __('entities.heroes.name') }}"
              :value="$hero->name" 
            />

            <x-entity-show.info-list-item label="{{ __('entities.factions.singular') }}">
              @if($hero->faction)
                <x-entity-show.info-link :href="route('admin.factions.show', [$hero->faction])">
                  {{ $hero->faction->name }}
                </x-entity-show.info-link>
              @else
                {{ __('entities.heroes.no_faction') }}
              @endif
            </x-entity-show.info-list-item>
            
            <x-entity-show.info-list-item 
              label="{{ __('entities.hero_races.singular') }}"
              :value="$hero->getGenderizedRaceName()"
            />
            
            <x-entity-show.info-list-item 
              label="{{ __('entities.heroes.gender') }}"
              :value="__('entities.heroes.genders.' . $hero->gender)" 
            />
            
            <x-entity-show.info-list-item 
              label="{{ __('entities.hero_classes.singular') }}"
              :value="$hero->getGenderizedClassName()"
            />
            
            <x-entity-show.info-list-item 
              label="{{ __('entities.hero_superclasses.singular') }}"
              :value="$hero->getGenderizedSuperclassName()"
            />
            
            <x-entity-show.info-list-item label="{{ __('admin.status') }}">
              @if($hero->isPublished())
                <x-badge variant="success">{{ __('admin.published') }}</x-badge>
              @else
                <x-badge variant="warning">{{ __('admin.draft') }}</x-badge>
              @endif
            </x-entity-show.info-list-item>

            @if($hero->trashed())
              <x-entity-show.info-list-item 
                label="{{ __('admin.deleted_at') }}"
                :value="$hero->deleted_at->format('d/m/Y H:i')" 
              />
            @endif
          </x-entity-show.info-list>
        </x-entity-show.info-block>

        {{-- Hero Image --}}
        @if($hero->hasImage())
          <x-entity-show.info-block title="entities.heroes.image">
            <div class="hero-image">
              <img src="{{ $hero->getImageUrl() }}" alt="{{ $hero->name }}">
            </div>
          </x-entity-show.info-block>
        @endif

        {{-- Attributes --}}
        <x-entity-show.info-block title="entities.heroes.attributes.title">
          <x-entity-show.info-list>
            <x-entity-show.info-list-item 
              label="{{ __('entities.heroes.attributes.agility') }}"
              :value="$hero->agility" 
            />
            
            <x-entity-show.info-list-item 
              label="{{ __('entities.heroes.attributes.mental') }}"
              :value="$hero->mental" 
            />
            
            <x-entity-show.info-list-item 
              label="{{ __('entities.heroes.attributes.will') }}"
              :value="$hero->will" 
            />
            
            <x-entity-show.info-list-item 
              label="{{ __('entities.heroes.attributes.strength') }}"
              :value="$hero->strength" 
            />
            
            <x-entity-show.info-list-item 
              label="{{ __('entities.heroes.attributes.armor') }}"
              :value="$hero->armor" 
            />
            
            <x-entity-show.info-list-item 
              label="{{ __('entities.heroes.attributes.health') }}"
              :value="$hero->health" 
            />
            
            <x-entity-show.info-list-item 
              label="{{ __('entities.heroes.total_attributes') }}"
              :value="$hero->total_attributes" 
            />
          </x-entity-show.info-list>
        </x-entity-show.info-block>

        {{-- Passive Abilities --}}
        <x-entity-show.info-block title="entities.heroes.passive_abilities">
          {{-- Class Passive --}}
            <x-entity-show.ability-card
              variant="passive"
              :name="$hero->heroClass->name"
              :description="$hero->heroClass->passive"
            />
            
            {{-- Hero Passive --}}
            <x-entity-show.ability-card
              variant="passive"
              :name="$hero->passive_name"
              :description="$hero->passive_description"
            />
        </x-entity-show.info-block>

        {{-- Active Abilities --}}
        @if($hero->heroAbilities->count() > 0)
          <x-entity-show.info-block title="entities.heroes.active_abilities">
            <div class="abilities-list">
              @foreach($hero->heroAbilities as $ability)
                <x-entity-show.ability-card
                  variant="active"
                  :name="$ability->name"
                  :description="$ability->description"
                  :cost="$ability->cost"
                  :attack-range="$ability->attackRange"
                  :attack-type="$ability->attack_type"
                  :attack-subtype="$ability->attackSubtype"
                  :area="$ability->area"
                />
              @endforeach
            </div>
          </x-entity-show.info-block>
        @endif

        {{-- Lore Text --}}
        @if($hero->lore_text)
          <x-entity-show.info-block title="entities.heroes.lore_text">
            <div class="prose">
              {!! $hero->lore_text !!}
            </div>
          </x-entity-show.info-block>
        @endif

        {{-- Epic Quote --}}
        @if($hero->epic_quote)
          <x-entity-show.info-block title="entities.heroes.epic_quote">
            <blockquote class="epic-quote">
              {!! $hero->epic_quote !!}
            </blockquote>
          </x-entity-show.info-block>
        @endif

        {{-- Timestamps --}}
        <x-entity-show.info-block title="admin.timestamps">
          <x-entity-show.info-list>
            <x-entity-show.info-list-item 
              label="{{ __('admin.created_at') }}"
              :value="$hero->created_at->format('d/m/Y H:i')" 
            />
            
            <x-entity-show.info-list-item 
              label="{{ __('admin.updated_at') }}"
              :value="$hero->updated_at->format('d/m/Y H:i')" 
            />
          </x-entity-show.info-list>
        </x-entity-show.info-block>
      </div>
    </div>
  </div>
</x-admin-layout>
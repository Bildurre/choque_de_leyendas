<x-admin-layout
  title="{{ $hero->name }}"
  headerTitle='{{ __("heroes.show") }}'
  containerTitle="{{ $hero->name }}"
  subtitle='{{ __("heroes.show_subtitle") }}'
  :createRoute="route('admin.heroes.create')"
  :backRoute="route('admin.heroes.index')"
>

  <x-detail-card 
    :title="$hero->name"
    :accentColor="$hero->faction->color"
    :model="$hero"
    :editRoute="route('admin.heroes.edit', $hero)"
    :deleteRoute="route('admin.heroes.destroy', $hero)"
    confirmAttribute="name"
  >
    <x-detail-section>
      @if($hero->image)
        <x-detail-image 
          :src="asset('storage/' . $hero->image)" 
          :alt="$hero->name" 
          size="lg"
        />
      @endif
      
      <x-previews.hero-preview :hero="$hero" />
      
    </x-detail-section>
    
    @if($hero->lore_text)
      <x-detail-section>
        <x-detail-text :content="$hero->lore_text" />
      </x-detail-section>
    @endif
    
    <x-detail-section title="{{ __('heroes.general_info') }}">
      <x-info-grid :columns="2">
        <x-info-grid-item label="{{ __('heroes.faction') }}" :value="$hero->faction->name ?? __('common.not_assigned')" />
        <x-info-grid-item label="{{ __('heroes.race') }}" :value="$hero->race->name ?? __('common.not_assigned')" />
        <x-info-grid-item label="{{ __('heroes.class') }}" :value="$hero->heroClass->name ?? __('common.not_assigned')" />
        <x-info-grid-item label="{{ __('heroes.superclass') }}" :value="$hero->heroClass->heroSuperclass->name ?? __('common.not_assigned')" />
        <x-info-grid-item label="{{ __('heroes.gender') }}" :value="$hero->gender == 'male' ? __('heroes.male') : __('heroes.female')" />
      </x-info-grid>
    </x-detail-section>
    
    <x-detail-section title="{{ __('heroes.attributes') }}">
      <x-attributes-grid 
        :columns="5" 
        :showTotal="true" 
        :totalValue="$hero->getTotalAttributePoints()" 
        :showHealth="true" 
        :healthValue="$hero->calculateHealth()"
      >
        <x-attribute-item label="{{ __('heroes.agility') }}" :value="$hero->agility" />
        <x-attribute-item label="{{ __('heroes.mental') }}" :value="$hero->mental" />
        <x-attribute-item label="{{ __('heroes.will') }}" :value="$hero->will" />
        <x-attribute-item label="{{ __('heroes.strength') }}" :value="$hero->strength" />
        <x-attribute-item label="{{ __('heroes.armor') }}" :value="$hero->armor" />
      </x-attributes-grid>
    </x-detail-section>
    
    @if($hero->passive_name)
      <x-detail-section title="{{ __('heroes.passive_name') }}: {{ $hero->passive_name }}">
        <x-detail-text :content="$hero->passive_description" isHtml="true" />
      </x-detail-section>
    @endif

    @if($hero->abilities->isNotEmpty())
      <x-detail-section title="{{ __('heroes.abilities') }}">
        <div class="hero-abilities-grid">
          @foreach($hero->abilities as $ability)
            <div class="hero-ability-item">
              <div class="ability-header">
                <div class="ability-cost">
                  <x-game.cost-display :cost="$ability->cost"/>
                </div>
                <h4 class="ability-name">{{ $ability->name }}</h4>
              </div>
              
              <div class="ability-meta">
                <span class="ability-type">{{ $ability->subtype->type->name ?? __('common.not_assigned') }}</span>
                <span class="ability-subtype">{{ $ability->subtype->name ?? __('common.not_assigned') }}</span>
                <span class="ability-range">{{ $ability->range->name ?? __('common.not_assigned') }}</span>
                @if($ability->area)
                  <span class="ability-area">{{ __('hero_abilities.area') }}</span>
                @endif
              </div>
              
              <div class="ability-description">
                {!! $ability->description !!}
              </div>
            </div>
          @endforeach
        </div>
      </x-detail-section>
    @endif

  </x-detail-card>

</x-admin-layout>
<x-public-layout>
  <div class="card-detail">
    <div class="card-detail__header">
      <h1 class="card-detail__name">{{ $card->name }}</h1>
      <p class="card-detail__faction">
        <a href="{{ route('public.factions.show', $card->faction) }}">
          {{ $card->faction->name }}
        </a>
      </p>
    </div>

    <div class="card-detail__content">
      <div class="card-detail__preview">
        <x-previews.preview-image :entity="$card" type="card" />
      </div>
      
      <div class="card-detail__info">
        <div class="card-detail__section">
          <h2>{{ __('public.cards.details') }}</h2>
          <dl class="card-detail__list">
            <dt>{{ __('entities.card_types.singular') }}</dt>
            <dd>{{ $card->cardType->name }}</dd>
            
            @if($card->equipmentType)
              <dt>{{ __('entities.equipment_types.singular') }}</dt>
              <dd>{{ $card->equipmentType->name }}</dd>
            @endif
            
            @if($card->cost)
              <dt>{{ __('common.cost') }}</dt>
              <dd>
                <x-cost-display :cost="$card->cost" />
              </dd>
            @endif
          </dl>
        </div>

        @if($card->lore_text)
          <div class="card-detail__section">
            <h2>{{ __('public.cards.lore') }}</h2>
            <div class="card-detail__lore">
              {!! $card->lore_text !!}
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</x-public-layout>
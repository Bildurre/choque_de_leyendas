<div class="pdf-entities">
  <div class="pdf-entities__actions">
    <x-button
      type="button"
      variant="primary"
      icon="download"
      class="generate-all-decks"
      data-url="{{ route('admin.pdf-export.generate-all-decks') }}"
    >
      {{ __('admin.pdf_export.generate_all_decks') }}
    </x-button>
  </div>
  
  <div class="pdf-entities__grid">
    @foreach($decks as $deck)
      <x-admin.pdf-entity-card 
        :entity="$deck"
        type="deck"
        :generate-url="route('admin.pdf-export.generate-deck', $deck)"
        :subtitle="$deck->faction->name"
      />
    @endforeach
  </div>
</div>
<div class="pdf-collection">    
  <div class="pdf-collection__downloads">
    
    
    @if (!$factionPdfs->isEmpty())
      <h3>{{ __('entities.factions.plural') }}</h3>
      <x-pdf.list :items="$factionPdfs" type="faction">
        @foreach($factionPdfs as $pdf)
          <x-pdf.public-item :pdf="$pdf" />
        @endforeach
      </x-pdf.list>
    @endif
    
    @if (!$deckPdfs->isEmpty())
      <h3>{{ __('entities.faction_decks.plural') }}</h3>
      <x-pdf.list :items="$deckPdfs" type="deck">
        @foreach($deckPdfs as $pdf)
          <x-pdf.public-item :pdf="$pdf" />
        @endforeach
      </x-pdf.list>
    @endif

    @if (!$otherPdfs->isEmpty())
      <h3>{{ __('pdf.collection.other_documents') }}</h3>
      <x-pdf.list :items="$otherPdfs" type="other">
        @foreach($otherPdfs as $pdf)
          <x-pdf.public-item :pdf="$pdf" />
        @endforeach
      </x-pdf.list>
    @endif

    @if (!$temporaryPdfs->isEmpty())
      <h3>{{ __('pdf.collection.your_pdfs') }}</h3>
      <p class="pdf-collection__section-description">
        {{ __('pdf.collection.temporary_description') }}
      </p>
      <x-pdf.list :items="$temporaryPdfs" type="temporary">
        @foreach($temporaryPdfs as $pdf)
          <x-pdf.public-item :pdf="$pdf" :showDelete="true" />
        @endforeach
      </x-pdf.list>
    @endif
    
    {{-- Empty state for downloads --}}
    @if($factionPdfs->isEmpty() && $deckPdfs->isEmpty() && $otherPdfs->isEmpty() && $temporaryPdfs->isEmpty())
      <div class="entity-list__empty">
        {{ __('pdf.collection.no_available') }}
      </div>
    @endif
  </div>        
</div>
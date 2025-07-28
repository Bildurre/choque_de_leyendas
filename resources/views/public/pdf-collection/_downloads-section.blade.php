<div class="pdf-collection">    
  <div class="pdf-collection__section">
    @if (!$factionPdfs->isEmpty())
      <div class="pdf-collection__downloads">
        <h3>{{ __('entities.factions.plural') }}</h3>
        <x-pdf.list :items="$factionPdfs" type="faction">
          @foreach($factionPdfs as $pdf)
            <x-pdf.public-item :pdf="$pdf" />
          @endforeach
        </x-pdf.list>
      </div>
    @endif
    
    @if (!$deckPdfs->isEmpty())
      <div class="pdf-collection__downloads">
        <h3>{{ __('entities.faction_decks.plural') }}</h3>
        <x-pdf.list :items="$deckPdfs" type="deck">
          @foreach($deckPdfs as $pdf)
            <x-pdf.public-item :pdf="$pdf" />
          @endforeach
        </x-pdf.list>
      </div>
    @endif

    @if (!$otherPdfs->isEmpty())
      <div class="pdf-collection__downloads">
        <h3>{{ __('pdf.collection.other_documents') }}</h3>
        <x-pdf.list :items="$otherPdfs" type="other">
          @foreach($otherPdfs as $pdf)
            <x-pdf.public-item :pdf="$pdf" />
          @endforeach
        </x-pdf.list>
      </div>
    @endif

    @if (!$temporaryPdfs->isEmpty())
      <div class="pdf-collection__downloads">
        <h3>{{ __('pdf.collection.your_pdfs') }}</h3>
        <p class="pdf-collection__section-description">
          {{ __('pdf.collection.temporary_description') }}
        </p>
        <x-pdf.list :items="$temporaryPdfs" type="temporary">
          @foreach($temporaryPdfs as $pdf)
            <x-pdf.public-item :pdf="$pdf" :showDelete="true" />
          @endforeach
        </x-pdf.list>
      </div>
    @endif
    
    {{-- Empty state for downloads --}}
    @if($factionPdfs->isEmpty() && $deckPdfs->isEmpty() && $otherPdfs->isEmpty() && $temporaryPdfs->isEmpty())
      <div class="entity-list__empty">
        {{ __('pdf.collection.no_available') }}
      </div>
    @endif
  </div>        
</div>
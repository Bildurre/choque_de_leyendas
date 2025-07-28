<div class="pdf-collection">    
  <div class="pdf-collection__downloads">
    
    <x-accordion id="pdf-downloads-accordion">
      @if($factionPdfs->isNotEmpty())
        <x-collapsible-section 
          title="{{ __('entities.factions.plural') }}" 
          id="downloads-factions"
          :collapsed="true"
        >
          <x-pdf.list :items="$factionPdfs" type="faction">
            @foreach($factionPdfs as $pdf)
              <x-pdf.public-item :pdf="$pdf" />
            @endforeach
          </x-pdf.list>
        </x-collapsible-section>
      @endif

      @if($deckPdfs->isNotEmpty())
        <x-collapsible-section 
          title="{{ __('entities.faction_decks.plural') }}" 
          id="downloads-decks"
          :collapsed="true"
        >
          <x-pdf.list :items="$deckPdfs" type="deck">
            @foreach($deckPdfs as $pdf)
              <x-pdf.public-item :pdf="$pdf" />
            @endforeach
          </x-pdf.list>
        </x-collapsible-section>
      @endif

      @if($otherPdfs->isNotEmpty())
        <x-collapsible-section 
          title="{{ __('pdf.collection.other_documents') }}" 
          id="downloads-other"
          :collapsed="true"
        >
          <x-pdf.list :items="$otherPdfs" type="other">
            @foreach($otherPdfs as $pdf)
              <x-pdf.public-item :pdf="$pdf" />
            @endforeach
          </x-pdf.list>
        </x-collapsible-section>
      @endif

      @if($temporaryPdfs->isNotEmpty())
        <x-collapsible-section 
          title="{{ __('pdf.collection.your_pdfs') }}" 
          id="downloads-temporary"
          :collapsed="true"
        >
          <p class="pdf-collection__section-description">
            {{ __('pdf.collection.temporary_description') }}
          </p>
          <x-pdf.list :items="$temporaryPdfs" type="temporary">
            @foreach($temporaryPdfs as $pdf)
              <x-pdf.public-item :pdf="$pdf" :showDelete="true" />
            @endforeach
          </x-pdf.list>
        </x-collapsible-section>
      @endif
    </x-accordion>
    
    {{-- Empty state for downloads --}}
    @if($factionPdfs->isEmpty() && $deckPdfs->isEmpty() && $otherPdfs->isEmpty() && $temporaryPdfs->isEmpty())
      <div class="entity-list__empty">
        {{ __('pdf.collection.no_available') }}
      </div>
    @endif
  </div>        
</div>
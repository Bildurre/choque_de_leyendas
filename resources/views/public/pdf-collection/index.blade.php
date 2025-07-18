<x-public-layout
  :title="__('pdf.downloads_page_title')"
  :metaDescription="__('pdf.downloads_page_description')"
  ogType="website"
>
  {{-- Header Block --}}
  @php
    $titleTranslations = [];
    $subtitleTranslations = [];
    
    foreach (config('laravellocalization.supportedLocales', ['es' => [], 'en' => []]) as $locale => $data) {
      $titleTranslations[$locale] = __('pdf.collection.title', [], $locale);
      
      $description = __('pdf.collection.description', [], $locale);
      $subtitleTranslations[$locale] = $description !== 'public.pdf_collection.description' ? $description : '';
    }
    
    $headerBlock = new \App\Models\Block([
      'type' => 'header',
      'title' => $titleTranslations,
      'subtitle' => $subtitleTranslations,
      'background_color' => 'none',
      'settings' => [
        'text_alignment' => 'left'
      ]
    ]);
  @endphp
  {!! $headerBlock->render() !!}

  {{-- Downloads Section --}}
  <section class="block">
    <div class="block__inner">
      <div class="pdf-collection">
        
        {{-- Available Downloads (Top Section) --}}
        <div class="pdf-collection__downloads">
          
          {{-- Factions PDFs --}}
          @if($factionPdfs->isNotEmpty())
            <div class="pdf-collection__section">
              <h2 class="pdf-collection__section-title">{{ __('entities.factions.plural') }}</h2>
              <x-pdf.list :items="$factionPdfs" type="faction">
                @foreach($factionPdfs as $pdf)
                  <x-pdf.public-item :pdf="$pdf" />
                @endforeach
              </x-pdf.list>
            </div>
          @endif
          
          {{-- Decks PDFs --}}
          @if($deckPdfs->isNotEmpty())
            <div class="pdf-collection__section">
              <h2 class="pdf-collection__section-title">{{ __('entities.faction_decks.plural') }}</h2>
              <x-pdf.list :items="$deckPdfs" type="deck">
                @foreach($deckPdfs as $pdf)
                  <x-pdf.public-item :pdf="$pdf" />
                @endforeach
              </x-pdf.list>
            </div>
          @endif
          
          {{-- Other PDFs --}}
          @if($otherPdfs->isNotEmpty())
            <div class="pdf-collection__section">
              <h2 class="pdf-collection__section-title">{{ __('pdf.collection.other_documents') }}</h2>
              <x-pdf.list :items="$otherPdfs" type="other">
                @foreach($otherPdfs as $pdf)
                  <x-pdf.public-item :pdf="$pdf" />
                @endforeach
              </x-pdf.list>
            </div>
          @endif
          
          {{-- Temporary PDFs --}}
          @if($temporaryPdfs->isNotEmpty())
            <div class="pdf-collection__section">
              <h2 class="pdf-collection__section-title">{{ __('pdf.collection.your_pdfs') }}</h2>
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
            <div class="pdf-collection__empty">
              <x-icon name="file-x" size="lg" />
              <p>{{ __('pdf.collection.no_available') }}</p>
            </div>
          @endif
          
        </div>
        
        {{-- Temporary Collection (Bottom Section) --}}
        @include('public.pdf-collection._temporary-collection')
        
      </div>
    </div>
  </section>
</x-public-layout>
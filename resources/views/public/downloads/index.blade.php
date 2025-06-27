<x-public-layout>
  {{-- Header Block --}}
  @php
    $titleTranslations = [];
    $subtitleTranslations = [];
    
    foreach (config('laravellocalization.supportedLocales', ['es' => [], 'en' => []]) as $locale => $data) {
      $titleTranslations[$locale] = __('public.downloads_and_collection', [], $locale);
      $subtitleTranslations[$locale] = __('public.downloads_and_collection_description', [], $locale);
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

  {{-- Available Downloads Block --}}
  <section class="block">
    <div class="block__inner">
      @include('public.downloads.partials.downloads-list', [
        'permanentPdfs' => $permanentPdfs,
        'sessionPdfs' => $sessionPdfs
      ])
    </div>
  </section>

  {{-- Collection Management Block --}}
  <section class="block">
    <div class="block__inner">
      @include('public.downloads.partials.collection-manager', [
        'collection' => $collection,
        'heroes' => $heroes,
        'cards' => $cards,
        'totalCount' => $totalCount,
        'totalCopies' => $totalCopies
      ])
    </div>
  </section>

  <script src="{{ asset('js/downloads-collection.js') }}" type="module"></script>
</x-public-layout>
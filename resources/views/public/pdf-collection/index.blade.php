<x-public-layout
  :title="__('pdf.downloads_page_title')"
  :metaDescription="__('pdf.downloads_page_description')"
  ogType="website"
>
  
  <x-page-background :image="asset('storage/images/pages/downloads-bg.jpeg')" />

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

  <section class="block">
    <div class="block__inner">      
      @include('public/pdf-collection._downloads-section')
    </div>
  </section>

  @env('local')
    <section class="block">
      <div class="block__inner">
        @include('public/pdf-collection._temporary-collection')
      </div>
    </section>
  @endenv
</x-public-layout>
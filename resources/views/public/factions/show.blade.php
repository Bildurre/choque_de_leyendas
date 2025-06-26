<x-public-layout>
  {{-- Page background with faction icon --}}
  @if($faction->hasImage())
    <x-page-background :image="$faction->getImageUrl()" />
  @endif

  {{-- Header Block con acciones --}}
  @php
    $titleTranslations = [];
    $subtitleTranslations = [];
    
    foreach (config('laravellocalization.supportedLocales', ['es' => [], 'en' => []]) as $locale => $data) {
      $titleTranslations[$locale] = $faction->getTranslation('name', $locale);
      $subtitleTranslations[$locale] = $faction->getTranslation('lore_text', $locale);
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

  @component('content.blocks.header', ['block' => $headerBlock])
    @slot('actions')
      <x-button
        type="button"
        variant="primary"
        icon="pdf-add"
        data-entity-type="faction"
        data-entity-id="{{ $faction->id }}"
        class="print-collection-add"
      >
        {{ __('public.collection.add_to_pdf') }}
      </x-button>
    @endslot
  @endcomponent

  {{-- Content Tabs --}}
  <section class="block">
    <div class="block__inner">
      {{-- Resto del contenido sin cambios --}}
    </div>
  </section>
</x-public-layout>
@php
  $expectedPdfCount = count(config('laravellocalization.supportedLocales', []));
@endphp

<div class="pdf-export__others">
  <x-pdf.list :items="collect(['counters-list', 'cut-out-counters'])" type="other">
    {{-- Counters List PDF - Item manual --}}
    {{-- @php
      $countersListPdfs = $existingPdfs['counters-list'] ?? null;
      $hasCountersListPdf = $countersListPdfs && count($countersListPdfs['pdfs']) > 0;
      $currentLocalePdf = $countersListPdfs['current_locale_pdf'] ?? null;
      $allPdfsGenerated = $hasCountersListPdf && count($countersListPdfs['pdfs']) === $expectedPdfCount;
    @endphp
    
    <x-pdf.item
      :title="__('pdf.counters_list')"
      type="other"
      :entityId="'counters-list'"
      :existingPdf="$currentLocalePdf"
      :generateRoute="route('admin.pdf-export.generate-counters-list')"
      :deleteRoute="$currentLocalePdf ? route('admin.pdf-export.destroy', $currentLocalePdf) : null"
    /> --}}
    
    {{-- Cut-out Counters PDF - Item manual --}}
    @php
      $cutOutCountersPdfs = $existingPdfs['cut-out-counters'] ?? null;
      $hasCutOutCountersPdf = $cutOutCountersPdfs && count($cutOutCountersPdfs['pdfs']) > 0;
      $currentLocaleCutOutPdf = $cutOutCountersPdfs['current_locale_pdf'] ?? null;
      $allCutOutPdfsGenerated = $hasCutOutCountersPdf && count($cutOutCountersPdfs['pdfs']) === $expectedPdfCount;
    @endphp
    
    <x-pdf.item
      :title="__('pdf.cut_out_counters')"
      type="other"
      :entityId="'cut-out-counters'"
      :existingPdf="$currentLocaleCutOutPdf"
      :generateRoute="route('admin.pdf-export.generate-cut-out-counters')"
      :deleteRoute="$currentLocaleCutOutPdf ? route('admin.pdf-export.destroy', $currentLocaleCutOutPdf) : null"
    />
  </x-pdf.list>
</div>
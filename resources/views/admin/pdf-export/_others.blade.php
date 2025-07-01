<div class="pdf-export__others">
  <x-pdf.list :items="collect(['counters-list'])" type="other">
    {{-- Counters List PDF - Item manual --}}
    @php
      $countersListPdfs = $existingPdfs['others']['counters-list'] ?? null;
      $hasCountersListPdf = $countersListPdfs && count($countersListPdfs['pdfs']) > 0;
      $currentLocalePdf = $countersListPdfs['current_locale_pdf'] ?? null;
      $expectedPdfCount = count(config('laravellocalization.supportedLocales', []));
      $allPdfsGenerated = $hasCountersListPdf && count($countersListPdfs['pdfs']) === $expectedPdfCount;
    @endphp
    
    <x-pdf.item
      :title="__('pdf.counters_list')"
      type="other"
      :entityId="'counters-list'"
      :existingPdf="$currentLocalePdf"
      :generateRoute="route('admin.pdf-export.generate-counters-list')"
      :deleteRoute="$currentLocalePdf ? route('admin.pdf-export.destroy', $currentLocalePdf) : null"
    />
    
    {{-- Aquí puedes agregar más items manuales en el futuro --}}
    
  </x-pdf.list>
</div>
<x-pdf.list 
  :items="$pages"
  type="admin"
  :empty-message="__('pages.no_printable_pages')"
>
  @foreach($pages as $page)
    @php
      $pageExistingPdfs = $existingPdfs[$page->id] ?? null;
      $hasPdf = $pageExistingPdfs && count($pageExistingPdfs['pdfs']) > 0;
      $currentLocalePdf = $pageExistingPdfs['current_locale_pdf'] ?? null;
    @endphp
    
    <x-pdf.item
      :title="$page->title"
      type="page"
      :entity-id="$page->id"
      :existing-pdf="$currentLocalePdf"
      :generate-route="route('admin.pdf-export.generate-page', $page)"
      :delete-route="$currentLocalePdf ? route('admin.pdf-export.destroy', $currentLocalePdf) : null"
    />
  @endforeach
</x-pdf.list>
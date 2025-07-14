<x-pdf.list 
  :items="$pages"
  type="admin"
  :empty-message="__('pages.no_printable_pages')"
>
  @foreach($pages as $page)
    <x-pdf.item
      :title="$page->title"
      type="page"
      :entity-id="$page->id"
      :existing-pdf="$existingPdfs[$page->id] ?? null"
      :generate-route="route('admin.pdf-export.generate-page', $page)"
      :delete-route="isset($existingPdfs[$page->id]) ? route('admin.pdf-export.destroy', $existingPdfs[$page->id]) : null"
    />
  @endforeach
</x-pdf.list>
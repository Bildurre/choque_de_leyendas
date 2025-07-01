<x-pdf.list 
  type="admin"
  :empty-message="__('common.no_results')"
>
  @forelse($customExports as $export)
    @php
      $pdfExists = isset($existingPdfs[$export['key']]);
      $pdf = $pdfExists ? $existingPdfs[$export['key']] : null;
    @endphp
    
    <x-pdf.admin-card
      type="custom"
      :pdf-exists="$pdfExists"
      :pdf="$pdf"
      :generate-url="route('admin.pdf-export.generate-custom')"
      :custom-data="$export"
    />
  @empty
    <x-slot:empty>true</x-slot:empty>
  @endforelse
</x-pdf.list>
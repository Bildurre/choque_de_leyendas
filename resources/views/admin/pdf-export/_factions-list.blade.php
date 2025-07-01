<x-pdf.list 
  type="admin"
  :empty-message="__('common.no_results')"
>
  @forelse($factions as $faction)
    @php
      $pdfExists = isset($existingPdfs[$faction->id]);
      $pdf = $pdfExists ? $existingPdfs[$faction->id] : null;
    @endphp
    
    <x-pdf.admin-card
      :entity="$faction"
      type="faction"
      :pdf-exists="$pdfExists"
      :pdf="$pdf"
      :generate-url="route('admin.pdf-export.generate-faction', $faction)"
    />
  @empty
    <x-slot:empty>true</x-slot:empty>
  @endforelse
</x-pdf.list>
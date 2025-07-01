<x-pdf.list 
  type="admin"
  :empty-message="__('common.no_results')"
>
  @forelse($decks as $deck)
    @php
      $pdfExists = isset($existingPdfs[$deck->id]);
      $pdf = $pdfExists ? $existingPdfs[$deck->id] : null;
    @endphp
    
    <x-pdf.admin-card
      :entity="$deck"
      type="deck"
      :pdf-exists="$pdfExists"
      :pdf="$pdf"
      :generate-url="route('admin.pdf-export.generate-deck', $deck)"
      :subtitle="$deck->faction->name"
    />
  @empty
    <x-slot:empty>true</x-slot:empty>
  @endforelse
</x-pdf.list>
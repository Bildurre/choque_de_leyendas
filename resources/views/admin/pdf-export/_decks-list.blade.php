<div class="pdf-export__decks">
  <x-pdf.list :items="$decks" type="deck">
    @foreach($decks as $deck)
      <x-pdf.item
        :title="$deck->name . ' (' . $deck->faction->name . ')'"
        type="deck"
        :entityId="$deck->id"
        :existingPdf="$existingPdfs[$deck->id] ?? null"
        :generateRoute="route('admin.pdf-export.generate-deck', $deck)"
        :deleteRoute="isset($existingPdfs[$deck->id]) ? route('admin.pdf-export.destroy', $existingPdfs[$deck->id]) : null"
      />
    @endforeach
  </x-pdf.list>
</div>
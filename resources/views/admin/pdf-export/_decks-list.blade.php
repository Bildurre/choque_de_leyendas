<div class="pdf-export__decks">
  <x-pdf.list :items="$decks" type="deck">
    @foreach($decks as $deck)
      @php
        $factionNames = $deck->factions->pluck('name')->join(', ');
      @endphp
      <x-pdf.item
        :title="$deck->name . ' (' . $factionNames . ')'"
        type="deck"
        :entityId="$deck->id"
        :existingPdf="$existingPdfs[$deck->id] ?? null"
        :generateRoute="route('admin.pdf-export.generate-deck', $deck)"
        :deleteRoute="isset($existingPdfs[$deck->id]) ? route('admin.pdf-export.destroy', $existingPdfs[$deck->id]) : null"
      />
    @endforeach
  </x-pdf.list>
</div>
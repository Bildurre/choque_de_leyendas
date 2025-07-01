<div class="pdf-export__factions">
  <x-pdf.list :items="$factions" type="faction">
    @foreach($factions as $faction)
      <x-pdf.item
        :title="$faction->name"
        type="faction"
        :entityId="$faction->id"
        :existingPdf="$existingPdfs['faction'][$faction->id] ?? null"
        :generateRoute="route('admin.pdf-export.generate-faction', $faction)"
        :deleteRoute="isset($existingPdfs['faction'][$faction->id]) ? route('admin.pdf-export.destroy', $existingPdfs['faction'][$faction->id]) : null"
      />
    @endforeach
  </x-pdf.list>
</div>
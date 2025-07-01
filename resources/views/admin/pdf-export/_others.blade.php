<div class="pdf-export__others">
  <x-pdf.list :items="$customExports" type="other">
    @foreach($customExports as $export)
      <x-pdf.item
        :title="$export['name']"
        type="other"
        :entityId="$export['key']"
        :existingPdf="$existingPdfs['custom'][$export['key']] ?? null"
        :generateRoute="route('admin.pdf-export.generate-custom')"
        :deleteRoute="isset($existingPdfs['custom'][$export['key']]) ? route('admin.pdf-export.destroy', $existingPdfs['custom'][$export['key']]) : null"
      >
      </x-pdf.item>
    @endforeach
  </x-pdf.list>
</div>
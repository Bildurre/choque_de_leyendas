<div class="pdf-entities">
  <div class="pdf-entities__actions">
    <x-button
      type="button"
      variant="primary"
      icon="download"
      class="generate-all-factions"
      data-url="{{ route('admin.pdf-export.generate-all-factions') }}"
    >
      {{ __('admin.pdf_export.generate_all_factions') }}
    </x-button>
  </div>
  
  <div class="pdf-entities__grid">
    @foreach($factions as $faction)
      <x-admin.pdf-entity-card 
        :entity="$faction"
        type="faction"
        :generate-url="route('admin.pdf-export.generate-faction', $faction)"
      />
    @endforeach
  </div>
</div>
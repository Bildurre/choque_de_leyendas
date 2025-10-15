<x-preview-management.card :title="__('export.export_faction')" class="preview-management-card--form">
  <p class="form-help-text">{{ __('export.export_faction_help') }}</p>
  
  <form action="{{ route('admin.export.json.faction') }}" method="POST">
    @csrf
    
    <x-form.select
      name="faction_id"
      :label="__('export.select_faction')"
      :options="$factions->pluck('name', 'id')"
      :placeholder="__('export.select_faction_placeholder')"
      required
    />

    <div class="action-buttons">
      <x-button type="submit" variant="primary" icon="download">
        {{ __('export.export_faction_button') }}
      </x-button>
    </div>
  </form>
</x-preview-management.card>

<x-preview-management.card :title="__('export.export_all_factions')" class="preview-management-card--form">
    
  <p class="form-help-text">{{ __('export.export_all_factions_help') }}</p>

  <form action="{{ route('admin.export.json.all-factions') }}" method="POST">
    @csrf
    
    <div class="action-buttons">
      <x-button type="submit" variant="primary" icon="download">
        {{ __('export.export_all_factions_button') }}
      </x-button>
    </div>
  </form>
</x-preview-management.card>
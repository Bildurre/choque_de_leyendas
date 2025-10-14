<x-preview-management.card :title="__('export.export_counters')" class="preview-management-card--form">
  <p class="form-help-text">{{ __('export.export_counters_help') }}</p>
  
  <form action="{{ route('admin.export.counters') }}" method="POST">
    @csrf
    <div class="action-buttons">
      <x-button type="submit" variant="primary" icon="download">
        {{ __('export.export_counters_button') }}
      </x-button>
    </div>
  </form>
</x-preview-management.card>
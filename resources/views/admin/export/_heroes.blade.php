<x-preview-management.card :title="__('export.export_heroes')" class="preview-management-card--form">
  <p class="form-help-text">{{ __('export.export_heroes_help') }}</p>
  
  <form action="{{ route('admin.export.heroes') }}" method="POST">
    @csrf
    <div class="action-buttons">
      <x-button type="submit" variant="primary" icon="download">
        {{ __('export.export_heroes_button') }}
      </x-button>
    </div>
  </form>
</x-preview-management.card>
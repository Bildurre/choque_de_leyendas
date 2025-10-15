{{-- Database Information --}}
<x-preview-management.section :title="__('export.database_information')">
  <x-preview-management.card :title="__('export.current_database')">
    <x-preview-management.stat-item 
      :label="__('export.database_name')" 
      :value="$databaseInfo['database_name']" 
    />
    <x-preview-management.stat-item 
      :label="__('export.table_count')" 
      :value="$databaseInfo['table_count']" 
    />
    <x-preview-management.stat-item 
      :label="__('export.total_size')" 
      :value="$databaseInfo['formatted_size']" 
      variant="disk" 
    />
  </x-preview-management.card>
</x-preview-management.section>

{{-- Database Export Section --}}
<x-preview-management.section :title="__('export.database')">
  <x-preview-management.card :title="__('export.new_export')" class="preview-management-card--form">
    <form action="{{ route('admin.export.database.create') }}" method="POST">
      @csrf
      
      <x-form.checkbox
        name="include_data"
        id="include_data"
        :label="__('export.include_data')"
        :help="__('export.include_data_help')"
        checked
      />
      
      <x-form.checkbox
        name="compress"
        id="compress"
        :label="__('export.compress_file')"
        :help="__('export.compress_file_help')"
      />
      
      <div class="action-buttons">
        <x-button type="submit" variant="primary" icon="download">
          {{ __('export.export_button') }}
        </x-button>
      </div>
    </form>
  </x-preview-management.card>
  
  <x-preview-management.card :title="__('export.other_exports')" class="preview-management-card--full">
    @if(count($exports) > 0)
      <div class="preview-management--grid">
        <form action="{{ route('admin.export.download-all') }}" method="POST">
          @csrf
          <x-button 
            type="submit" 
            variant="primary" 
            icon="download"
          >
            {{ __('export.download_all') }}
          </x-button>
        </form>

        <form action="{{ route('admin.export.delete-all') }}" method="POST">
          @csrf
          @method('DELETE')
          <x-button 
            type="submit" 
            variant="danger" 
            icon="trash"
            class="confirm-action" 
            data-confirm="{{ __('export.confirm_delete_all') }}"
          >
            {{ __('export.delete_all') }}
          </x-button>
        </form>
      </div>

      <x-export.list :items="collect($exports)">
        @foreach($exports as $export)
          <x-export.item
            :filename="$export['filename']"
            :size="$export['formatted_size']"
            :date="$export['formatted_date']"
            :downloadRoute="route('admin.export.download', ['filename' => $export['filename']])"
            :deleteRoute="route('admin.export.delete-single', ['filename' => $export['filename']])"
            :restoreRoute="route('admin.export.database.restore', ['filename' => $export['filename']])"
          />
        @endforeach
      </x-export.list>
    @else
      <x-export.list :items="collect([])">
      </x-export.list>
    @endif
  </x-preview-management.card>
</x-preview-management.section>

{{-- Database Restore Section --}}
<x-preview-management.section :title="__('export.database_restore')">
  <x-preview-management.card :title="__('export.upload_backup')" class="preview-management-card--form">
    <p class="form-help-text">{{ __('export.upload_backup_help') }}</p>
    
    <form action="{{ route('admin.export.database.upload') }}" method="POST" enctype="multipart/form-data">
      @csrf
      
      <x-form.file
        name="database_file"
        :label="__('export.select_file')"
        accept=".sql,.zip"
        required
      />

      <div class="action-buttons">
        <x-button type="submit" variant="primary" icon="upload">
          {{ __('export.upload_button') }}
        </x-button>
      </div>
    </form>
  </x-preview-management.card>
</x-preview-management.section>
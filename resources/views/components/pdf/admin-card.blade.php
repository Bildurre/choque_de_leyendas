@props([
  'entity',
  'type', // 'faction', 'deck', 'custom'
  'pdfExists' => false,
  'pdf' => null,
  'generateUrl',
  'subtitle' => null,
  'customData' => [], // For custom type PDFs
])

<div class="pdf-admin-card" data-entity-id="{{ $entity->id ?? $customData['key'] ?? '' }}" data-entity-type="{{ $type }}">
  <div class="pdf-admin-card__info">
    <x-badge :variant="$pdfExists ? 'success' : 'danger'" size="sm">
      @if($pdfExists)
        <x-icon name="check-circle" size="xs" />
      @else
        <x-icon name="x-circle" size="xs" />
      @endif
    </x-badge>
    <h4 class="pdf-admin-card__name">
      {{ $entity->name ?? $customData['name'] ?? '' }}
    </h4>
  </div>
  
  <div class="pdf-admin-card__actions">
    @if($type === 'custom')
      <form action="{{ $generateUrl }}" method="POST" class="inline">
        @csrf
        <input type="hidden" name="template" value="{{ $customData['template'] ?? '' }}">
        <x-button
          type="submit"
          :variant="$pdfExists ? 'secondary' : 'primary'"
          size="sm"
        >
          {{ $pdfExists ? __('pdf.regenerate') : __('pdf.generate') }}
        </x-button>
      </form>
    @else
      <x-action-button
        route="{{ $generateUrl }}"
        method='POST'
        icon="{{ $pdfExists ? 'refresh' : 'plus' }}"
        variant="publish"
      >
      </x-action-button>
    @endif
    
    @if($pdfExists && $pdf)
      <x-action-button
        route="{{ route('admin.pdf-export.destroy', $pdf) }}"
        method='DELETE'
        icon="trash"
        confirmMessage="{{ __('admin.pdf_export.confirm_delete') }}"
        variant="delete"
      >
      </x-action-button>
    @endif
  </div>
</div>
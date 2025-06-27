@props(['pdf'])

<div class="admin-pdf-card" data-pdf-id="{{ $pdf->id }}">
  <div class="admin-pdf-card__header">
    <x-icon name="{{ $pdf->is_permanent ? 'hard-drive' : 'clock' }}" 
            class="admin-pdf-card__icon admin-pdf-card__icon--{{ $pdf->is_permanent ? 'permanent' : 'temporary' }}" />
    <div class="admin-pdf-card__info">
      <h4 class="admin-pdf-card__name">{{ $pdf->filename }}</h4>
      <div class="admin-pdf-card__meta">
        <span class="admin-pdf-card__type">{{ ucfirst($pdf->type) }}</span>
        <span class="admin-pdf-card__separator">•</span>
        <span class="admin-pdf-card__size">{{ $pdf->formatted_size }}</span>
        <span class="admin-pdf-card__separator">•</span>
        <span class="admin-pdf-card__date">{{ $pdf->created_at->format('d/m/Y H:i') }}</span>
      </div>
      @if($pdf->metadata)
        <div class="admin-pdf-card__metadata">
          @foreach($pdf->metadata as $key => $value)
            @if(in_array($key, ['faction_name', 'deck_name']))
              <span class="admin-pdf-card__tag">{{ $value }}</span>
            @endif
          @endforeach
        </div>
      @endif
    </div>
  </div>
  
  <div class="admin-pdf-card__actions">
    <a href="{{ $pdf->url }}" 
       target="_blank"
       class="admin-pdf-card__action admin-pdf-card__action--view"
       title="{{ __('admin.view') }}">
      <x-icon name="eye" />
    </a>
    <a href="{{ route('public.downloads.download', $pdf) }}" 
       class="admin-pdf-card__action admin-pdf-card__action--download"
       download
       title="{{ __('admin.download') }}">
      <x-icon name="download" />
    </a>
    <button type="button"
            class="admin-pdf-card__action admin-pdf-card__action--delete"
            data-pdf-id="{{ $pdf->id }}"
            data-url="{{ route('admin.pdf-export.destroy', $pdf) }}"
            title="{{ __('admin.delete') }}">
      <x-icon name="trash-2" />
    </button>
  </div>
</div>
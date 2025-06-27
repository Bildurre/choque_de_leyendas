<div class="recent-pdfs">
  @if($recentPdfs->isEmpty())
    <div class="recent-pdfs__empty">
      <x-icon name="file-x" class="recent-pdfs__empty-icon" />
      <p class="recent-pdfs__empty-text">{{ __('admin.pdf_export.no_recent_pdfs') }}</p>
    </div>
  @else
    <div class="recent-pdfs__grid">
      @foreach($recentPdfs as $pdf)
        <x-admin.pdf-card :pdf="$pdf" />
      @endforeach
    </div>
  @endif
</div>
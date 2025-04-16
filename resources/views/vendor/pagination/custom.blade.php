@if ($paginator->hasPages())
  <nav class="pagination-container">
    @if($paginator->total() > 0)
      <div class="pagination-info">
        Mostrando {{ $paginator->firstItem() }} a {{ $paginator->lastItem() }} de {{ $paginator->total() }} resultados
      </div>
    @endif

    <ul class="pagination">
      {{-- Previous Page Link --}}
      @if ($paginator->onFirstPage())
        <li class="pagination-item pagination-item--disabled">
          <span class="pagination-link pagination-link--disabled">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
          </span>
        </li>
      @else
        <li class="pagination-item">
          <a href="{{ $paginator->previousPageUrl() }}" class="pagination-link" rel="prev">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
          </a>
        </li>
      @endif

      {{-- Pagination Elements --}}
      @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
          <li class="pagination-item pagination-item--separator">
            <span class="pagination-link pagination-link--separator">{{ $element }}</span>
          </li>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
          @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
              <li class="pagination-item pagination-item--active">
                <span class="pagination-link pagination-link--active">{{ $page }}</span>
              </li>
            @else
              <li class="pagination-item">
                <a href="{{ $url }}" class="pagination-link">{{ $page }}</a>
              </li>
            @endif
          @endforeach
        @endif
      @endforeach

      {{-- Next Page Link --}}
      @if ($paginator->hasMorePages())
        <li class="pagination-item">
          <a href="{{ $paginator->nextPageUrl() }}" class="pagination-link" rel="next">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
          </a>
        </li>
      @else
        <li class="pagination-item pagination-item--disabled">
          <span class="pagination-link pagination-link--disabled">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
          </span>
        </li>
      @endif
    </ul>
  </nav>
@endif
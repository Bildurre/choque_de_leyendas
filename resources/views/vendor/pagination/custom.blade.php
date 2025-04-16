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
            <x-icon name="chevron-left" />
          </span>
        </li>
      @else
        <li class="pagination-item">
          <a href="{{ $paginator->previousPageUrl() }}" class="pagination-link" rel="prev">
            <x-icon name="chevron-left" />
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
            <x-icon name="chevron-right" />
          </a>
        </li>
      @else
        <li class="pagination-item pagination-item--disabled">
          <span class="pagination-link pagination-link--disabled">
            <x-icon name="chevron-right" />
          </span>
        </li>
      @endif
    </ul>
  </nav>
@endif
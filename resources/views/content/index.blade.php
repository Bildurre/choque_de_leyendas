<x-public-layout>
  <div class="content-archive">
    <div class="content-archive__header">
      <h1 class="content-archive__title">{{ __('pages.all_pages') }}</h1>
    </div>
    
    <div class="content-archive__grid">
      @forelse($pages as $page)
        <div class="content-card">
          <div class="content-card__image">
            @if($page->image)
              <img src="{{ asset('storage/' . $page->image) }}" alt="{{ $page->title }}">
            @else
              <div class="content-card__placeholder"></div>
            @endif
          </div>
          
          <div class="content-card__body">
            <h2 class="content-card__title">
              <a href="{{ route('content.page', $page->slug) }}">{{ $page->title }}</a>
            </h2>
            
            @if($page->description)
              <div class="content-card__description">
                {{ Str::limit(strip_tags($page->description), 150) }}
              </div>
            @endif
          </div>
          
          <div class="content-card__footer">
            <a href="{{ route('content.page', $page->slug) }}" class="btn btn--secondary btn--sm">
              {{ __('pages.read_more') }}
            </a>
          </div>
        </div>
      @empty
        <div class="content-archive__empty">
          {{ __('pages.no_pages') }}
        </div>
      @endforelse
    </div>
  </div>
</x-public-layout>
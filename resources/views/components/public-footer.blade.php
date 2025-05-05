<footer class="public-footer">
  <div class="footer-container">
    <div class="footer-logo">
      <a href="{{ route('welcome') }}" class="logo-link">
        <x-logo />
      </a>
    </div>
    
    <div class="footer-nav">
      <div class="footer-section">
        <h3 class="footer-title">{{ __('public.footer.explore') }}</h3>
        <ul class="footer-links">
          <li><a href="#">{{ __('public.footer.factions') }}</a></li>
          <li><a href="#">{{ __('public.footer.heroes') }}</a></li>
          <li><a href="#">{{ __('public.footer.cards') }}</a></li>
        </ul>
      </div>
      
      <div class="footer-section">
        <h3 class="footer-title">{{ __('public.footer.rules') }}</h3>
        <ul class="footer-links">
          <li><a href="#">{{ __('public.footer.basics') }}</a></li>
          <li><a href="#">{{ __('public.footer.advanced') }}</a></li>
          <li><a href="#">{{ __('public.footer.download') }}</a></li>
        </ul>
      </div>
    </div>
    
    <div class="footer-copyright">
      &copy; {{ date('Y') }} Alanda - Choque de Leyendas
    </div>
  </div>
</footer>
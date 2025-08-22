<footer class="public-footer">
  <div class="footer-container">
    <div class="footer-license">
      <div xmlns:cc="http://creativecommons.org/ns#" xmlns:dct="http://purl.org/dc/terms/">
        <a property="dct:title" rel="cc:attributionURL" href="https://leyendas.espadasdeceniza.com">
          {{ __('common.full_title') }}
        </a>

        @if (app()->getLocale()=='es')
          <span>por</span>
          <a rel="cc:attributionURL dct:creator" property="cc:attributionName" href="https://bildurre.net">Bildurre</a>
          <span>esta bajo licencia</span>
          <a href="http://creativecommons.org/licenses/by-nc-sa/4.0/?ref=chooser-v1" target="_blank" rel="license noopener noreferrer" class="license-cc-link">
            <span>CC BY-NC-SA 4.0</span>
            <img src="https://mirrors.creativecommons.org/presskit/icons/cc.svg?ref=chooser-v1">
            <img src="https://mirrors.creativecommons.org/presskit/icons/by.svg?ref=chooser-v1">
            <img src="https://mirrors.creativecommons.org/presskit/icons/nc.svg?ref=chooser-v1">
            <img src="https://mirrors.creativecommons.org/presskit/icons/sa.svg?ref=chooser-v1">
          </a>
          <span>.</span>
        @elseif (app()->getLocale()=='en')
          <span>by</span>
          <a rel="cc:attributionURL dct:creator" property="cc:attributionName" href="https://bildurre.net">Bildurre</a>
          <span>is under</span>
          <a href="http://creativecommons.org/licenses/by-nc-sa/4.0/?ref=chooser-v1" target="_blank" rel="license noopener noreferrer" class="license-cc-link">
            <span>CC BY-NC-SA 4.0</span>
            <img src="https://mirrors.creativecommons.org/presskit/icons/cc.svg?ref=chooser-v1">
            <img src="https://mirrors.creativecommons.org/presskit/icons/by.svg?ref=chooser-v1">
            <img src="https://mirrors.creativecommons.org/presskit/icons/nc.svg?ref=chooser-v1">
            <img src="https://mirrors.creativecommons.org/presskit/icons/sa.svg?ref=chooser-v1">
          </a>
          <span>license.</span>
        @endif
      </div>
    </div>
  </div>
</footer>
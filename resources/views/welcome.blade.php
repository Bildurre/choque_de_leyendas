<!-- resources/views/welcome.blade.php -->
<x-public-layout>
  <div class="welcome-container">
    <div class="welcome-content">
      <h1 class="welcome-title">Alanda - Choque de Leyendas</h1>
      <p class="welcome-subtitle">{{ __('public.welcome.subtitle') }}</p>
      
      <div class="welcome-description">
        <p>{{ __('public.welcome.description') }}</p>
      </div>
      
      <div class="welcome-actions">
        <a href="{{ route('login') }}" class="btn btn--primary">{{ __('public.welcome.login') }}</a>
      </div>
    </div>
    
    <div class="welcome-image">
      <!-- Placeholder for a welcome image -->
      <div class="image-placeholder">
        <!-- Se puede reemplazar con una imagen real -->
        <div class="placeholder-content">{{ __('public.welcome.game_art') }}</div>
      </div>
    </div>
  </div>
  
  <!-- Secciones adicionales con Lorem ipsum para pruebas de scroll -->
  <div class="welcome-sections">
    <section class="welcome-section">
      <h2 class="section-title">{{ __('public.welcome.section_title_1') }}</h2>
      <div class="section-content">
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam in dui mauris. Vivamus hendrerit arcu sed erat molestie vehicula. Sed auctor neque eu tellus rhoncus ut eleifend nibh porttitor. Ut in nulla enim. Phasellus molestie magna non est bibendum non venenatis nisl tempor. Suspendisse dictum feugiat nisl ut dapibus. Mauris iaculis porttitor posuere. Praesent id metus massa, ut blandit odio.</p>
        
        <p>Proin quis tortor orci. Etiam at risus et justo dignissim congue. Donec congue lacinia dui, a porttitor lectus condimentum laoreet. Nunc eu ullamcorper orci. Quisque eget odio ac lectus vestibulum faucibus eget in metus. In pellentesque faucibus vestibulum. Nulla at nulla justo, eget luctus tortor. Nulla facilisi. Duis aliquet egestas purus in blandit.</p>
      </div>
    </section>
    
    <section class="welcome-section">
      <h2 class="section-title">{{ __('public.welcome.section_title_2') }}</h2>
      <div class="section-content">
        <div class="faction-grid">
          <div class="faction-card">
            <div class="faction-image">
              <div class="placeholder-content">{{ __('public.welcome.faction_image') }} 1</div>
            </div>
            <h3 class="faction-name">{{ __('public.welcome.faction_name') }} 1</h3>
            <p class="faction-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam in dui mauris. Vivamus hendrerit arcu sed erat molestie vehicula.</p>
          </div>
          
          <div class="faction-card">
            <div class="faction-image">
              <div class="placeholder-content">{{ __('public.welcome.faction_image') }} 2</div>
            </div>
            <h3 class="faction-name">{{ __('public.welcome.faction_name') }} 2</h3>
            <p class="faction-description">Sed auctor neque eu tellus rhoncus ut eleifend nibh porttitor. Ut in nulla enim. Phasellus molestie magna non est bibendum non venenatis.</p>
          </div>
          
          <div class="faction-card">
            <div class="faction-image">
              <div class="placeholder-content">{{ __('public.welcome.faction_image') }} 3</div>
            </div>
            <h3 class="faction-name">{{ __('public.welcome.faction_name') }} 3</h3>
            <p class="faction-description">Proin quis tortor orci. Etiam at risus et justo dignissim congue. Donec congue lacinia dui, a porttitor lectus condimentum laoreet.</p>
          </div>
        </div>
      </div>
    </section>
    
    <section class="welcome-section">
      <h2 class="section-title">{{ __('public.welcome.section_title_3') }}</h2>
      <div class="section-content">
        <p>Curabitur lobortis id lorem id bibendum. Ut id consectetur magna. Quisque volutpat augue enim, pulvinar lobortis nibh lacinia at. Vestibulum nec erat ut mi sollicitudin porttitor id sit amet risus. Nam tempus vel odio vitae aliquam. In imperdiet eros id lacus vestibulum vestibulum. Suspendisse fermentum sem sagittis ante venenatis egestas quis vel justo. Maecenas semper suscipit nunc, sed aliquam sapien convallis eu.</p>
        
        <div class="feature-list">
          <div class="feature-item">
            <div class="feature-icon">
              <div class="placeholder-content">{{ __('public.welcome.feature_icon') }}</div>
            </div>
            <h3 class="feature-title">{{ __('public.welcome.feature_title_1') }}</h3>
            <p class="feature-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam in dui mauris.</p>
          </div>
          
          <div class="feature-item">
            <div class="feature-icon">
              <div class="placeholder-content">{{ __('public.welcome.feature_icon') }}</div>
            </div>
            <h3 class="feature-title">{{ __('public.welcome.feature_title_2') }}</h3>
            <p class="feature-description">Vivamus hendrerit arcu sed erat molestie vehicula. Sed auctor neque eu tellus.</p>
          </div>
          
          <div class="feature-item">
            <div class="feature-icon">
              <div class="placeholder-content">{{ __('public.welcome.feature_icon') }}</div>
            </div>
            <h3 class="feature-title">{{ __('public.welcome.feature_title_3') }}</h3>
            <p class="feature-description">Ut in nulla enim. Phasellus molestie magna non est bibendum non venenatis nisl tempor.</p>
          </div>
          
          <div class="feature-item">
            <div class="feature-icon">
              <div class="placeholder-content">{{ __('public.welcome.feature_icon') }}</div>
            </div>
            <h3 class="feature-title">{{ __('public.welcome.feature_title_4') }}</h3>
            <p class="feature-description">Suspendisse dictum feugiat nisl ut dapibus. Mauris iaculis porttitor posuere.</p>
          </div>
        </div>
      </div>
    </section>
    
    <section class="welcome-section">
      <h2 class="section-title">{{ __('public.welcome.section_title_4') }}</h2>
      <div class="section-content">
        <p>Nulla malesuada pellentesque elit eget gravida cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra.</p>
        
        <p>Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.</p>
        
        <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra.</p>
      </div>
    </section>
    
    <section class="welcome-section cta-section">
      <h2 class="section-title">{{ __('public.welcome.cta_title') }}</h2>
      <div class="section-content">
        <p class="cta-description">{{ __('public.welcome.cta_description') }}</p>
        <div class="cta-buttons">
          <a href="{{ route('login') }}" class="btn btn--primary">{{ __('public.welcome.login') }}</a>
        </div>
      </div>
    </section>
  </div>
</x-public-layout>
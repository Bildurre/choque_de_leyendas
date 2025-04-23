<aside class="admin-sidebar">
  <nav class="sidebar-nav">
    <x-sidebar-section >
      <li>
        <x-sidebar-nav-link 
          :route="route('admin.dashboard')"
          :active="request()->routeIs('admin.dashboard')" 
          icon="mono-yellow"
        >
          Dashboard
        </x-sidebar-nav-link>
      </li>
    </x-sidebar-section>
    
    <x-sidebar-section title="Componentes">
      <li>
        <x-sidebar-nav-link 
          :route="route('admin.factions.index')"
          :active="request()->routeIs('admin.factions.*')" 
          icon="mono-yellow"
        >
          Facciones
        </x-sidebar-nav-link>
      </li>
      <li>
        <x-sidebar-nav-link 
          :route="route('admin.hero-abilities.index')"
          :active="request()->routeIs('admin.hero-abilities.*')" 
          icon="mono-yellow"
        >
          Habilidades
        </x-sidebar-nav-link>
      </li>
      <li>
        <x-sidebar-nav-link 
          :route="route('admin.heroes.index')"
          :active="request()->routeIs('admin.heroes.*')" 
          icon="mono-yellow"
        >
          Héroes
        </x-sidebar-nav-link>
      </li>
    </x-sidebar-section>

    <x-sidebar-section title="Balance">
      <li>
        <x-sidebar-nav-link 
          :route="route('admin.hero-attributes-configurations.edit')"
          :active="request()->routeIs('admin.hero-attributes-configurations.*')" 
          icon="mono-yellow"
        >
          Configuración de Atributos
        </x-sidebar-nav-link>
      </li>
      <li>
        <x-sidebar-nav-link 
          :route="route('admin.hero-superclasses.index')"
          :active="request()->routeIs('admin.hero-superclasses.*')" 
          icon="mono-yellow"
        >
          Superclases
        </x-sidebar-nav-link>
      </li>
      <li>
        <x-sidebar-nav-link 
          :route="route('admin.hero-classes.index')"
          :active="request()->routeIs('admin.hero-classes.*')"  
          icon="mono-yellow"
        >
          Clases
        </x-sidebar-nav-link>
      </li>

      <li>
        <x-sidebar-nav-link 
          :route="route('admin.hero-races.index')"
          :active="request()->routeIs('admin.hero-races.*')" 
          icon="mono-yellow"
        >
          Razas
        </x-sidebar-nav-link>
      </li>
    </x-sidebar-section>

    <x-sidebar-section title="Habilidades">
      <li>
        <x-sidebar-nav-link 
          :route="route('admin.attack-subtypes.index')"
          :active="request()->routeIs('admin.attack-subtypes.*')" 
          icon="mono-yellow"
        >
          Subtipos
        </x-sidebar-nav-link>
      </li>
      <li>
        <x-sidebar-nav-link 
          :route="route('admin.attack-ranges.index')"
          :active="request()->routeIs('admin.attack-ranges.*')" 
          icon="mono-yellow"
        >
          Rangos
        </x-sidebar-nav-link>
      </li>
    </x-sidebar-section>
  </nav>
</aside>
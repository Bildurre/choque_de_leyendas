<aside class="admin-sidebar">
  <nav class="sidebar-nav">
    <!-- Dashboard link (standalone, before any groups) -->
    <div class="sidebar-dashboard-link">
      <x-navigation.sidebar-nav-link 
        :route="route('admin.dashboard')"
        :active="request()->routeIs('admin.dashboard')" 
        icon="mono-blue"
      >
        Dashboard
      </x-navigation.sidebar-nav-link>
    </div>
    
    <x-navigation.sidebar-section title="Componentes">
      <li>
        <x-navigation.sidebar-nav-link 
          :route="route('admin.factions.index')"
          :active="request()->routeIs('admin.factions.*')" 
          icon="mono-red"
        >
          Facciones
        </x-navigation.sidebar-nav-link>
      </li>
      <li>
        <x-navigation.sidebar-nav-link 
          :route="route('admin.hero-abilities.index')"
          :active="request()->routeIs('admin.hero-abilities.*')" 
          icon="mono-green"
        >
          Habilidades
        </x-navigation.sidebar-nav-link>
      </li>
      <li>
        <x-navigation.sidebar-nav-link 
          route="#"
          :active="request()->routeIs('admin.heroes.*')" 
          icon="mono-blue"
        >
          Héroes
        </x-navigation.sidebar-nav-link>
      </li>
    </x-navigation.sidebar-section>

    <!-- Balance Section -->
    <x-navigation.sidebar-section title="Balance">
      <li>
        <x-navigation.sidebar-nav-link 
          :route="route('admin.hero-attributes.edit')"
          :active="request()->routeIs('admin.hero-attributes.*')" 
          icon="red-green"
        >
          Configuración de Atributos
        </x-navigation.sidebar-nav-link>
      </li>
      <li>
        <x-navigation.sidebar-nav-link 
          :route="route('admin.superclasses.index')"
          :active="request()->routeIs('admin.superclasses.*')" 
          icon="green-blue"
        >
          Superclases
        </x-navigation.sidebar-nav-link>
      </li>
      <li>
        <x-navigation.sidebar-nav-link 
          :route="route('admin.hero-classes.index')"
          :active="request()->routeIs('admin.hero-classes.*')" 
          icon="mono-green"
        >
          Clases
        </x-navigation.sidebar-nav-link>
      </li>
    </x-navigation.sidebar-section>

    <!-- Habilidades Section -->
    <x-navigation.sidebar-section title="Habilidades">
      <li>
        <x-navigation.sidebar-nav-link 
          :route="route('admin.attack-types.index')"
          :active="request()->routeIs('admin.attack-types.*')" 
          icon="mono-red"
        >
          Tipos
        </x-navigation.sidebar-nav-link>
      </li>
      <li>
        <x-navigation.sidebar-nav-link 
          :route="route('admin.attack-subtypes.index')"
          :active="request()->routeIs('admin.attack-subtypes.*')" 
          icon="mono-green"
        >
          Subtipos
        </x-navigation.sidebar-nav-link>
      </li>
      <li>
        <x-navigation.sidebar-nav-link 
          :route="route('admin.attack-ranges.index')"
          :active="request()->routeIs('admin.attack-ranges.*')" 
          icon="mono-blue"
        >
          Rangos
        </x-navigation.sidebar-nav-link>
      </li>
    </x-navigation.sidebar-section>
  </nav>
</aside>
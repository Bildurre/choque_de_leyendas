import Alpine from 'alpinejs'

// Global Store (optional but recommended)
document.addEventListener('alpine:init', () => {
  Alpine.store('app', {
    darkMode: false,
    toggleDarkMode() {
      this.darkMode = !this.darkMode
      document.documentElement.classList.toggle('dark-mode', this.darkMode)
    }
  })
})

window.Alpine = Alpine
Alpine.start()
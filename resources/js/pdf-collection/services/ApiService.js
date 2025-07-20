// resources/js/pdf-collection/services/ApiService.js
export default class ApiService {
  constructor() {
    this.baseHeaders = {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      'X-Requested-With': 'XMLHttpRequest',
    };
  }
  
  async fetchJson(url, options = {}) {
    const response = await fetch(url, {
      ...options,
      headers: {
        ...this.baseHeaders,
        ...(options.headers || {})
      }
    });
    return response.json();
  }
  
  async getCollectionStatus() {
    return this.fetchJson('/pdf-collection/status');
  }
  
  async getCollectionItems() {
    return this.fetchJson('/pdf-collection/items');
  }
  
  async addToCollection(data) {
    return this.fetchJson('/pdf-collection/add', {
      method: 'POST',
      body: JSON.stringify(data)
    });
  }
  
  async removeFromCollection(type, entityId) {
    return this.fetchJson('/pdf-collection/remove', {
      method: 'POST',
      body: JSON.stringify({
        type: type,
        entity_id: parseInt(entityId)
      })
    });
  }
  
  async updateCopies(type, entityId, copies) {
    return this.fetchJson('/pdf-collection/update-copies', {
      method: 'POST',
      body: JSON.stringify({
        type: type,
        entity_id: parseInt(entityId),
        copies: copies
      })
    });
  }
  
  async generatePdf() {
    return this.fetchJson('/pdf-collection/generate', {
      method: 'POST',
      body: JSON.stringify({})
    });
  }
}
// frontend/src/services/api.ts

const API_BASE_URL = 'http://localhost/DMR_project/backend/public';

export const api = {
  async get(endpoint: string) {
    const response = await fetch(`${API_BASE_URL}${endpoint}`, {
      method: 'GET',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
    });
    return response.json();
  },

  async post(endpoint: string, body: any) {
    const response = await fetch(`${API_BASE_URL}${endpoint}`, {
      method: 'POST',
      credentials: 'include', 
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(body),
    });
    return response.json();
  },

  async postFormData(endpoint: string, formData: FormData) {
    const response = await fetch(`${API_BASE_URL}${endpoint}`, {
      method: 'POST',
      credentials: 'include',
      body: formData,
    });
    return response.json();
  },

  async put(endpoint: string, body: any) {
    const response = await fetch(`${API_BASE_URL}${endpoint}`, {
      method: 'PUT',
      credentials: 'include', 
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(body),
    });
    return response.json();
  },

  async delete(endpoint: string) {
    const response = await fetch(`${API_BASE_URL}${endpoint}`, {
      method: 'DELETE',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
    });
    return response.json();
  }
};
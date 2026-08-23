// frontend/src/services/api.ts

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost/DMR_project/backend/public';

async function handleResponse(response: Response) {
  const data = await response.json().catch(() => ({
    status: 'error',
    message: response.statusText || 'Network / Server Error',
  }));

  if (!response.ok) {
    throw new Error(data.message || `Request failed with status ${response.status}`);
  }
  return data;
}

export const api = {
  async get(endpoint: string) {
    const response = await fetch(`${API_BASE_URL}${endpoint}`, {
      method: 'GET',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
    });
    return handleResponse(response);
  },

  async post(endpoint: string, body: any) {
    const response = await fetch(`${API_BASE_URL}${endpoint}`, {
      method: 'POST',
      credentials: 'include', 
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(body),
    });
    return handleResponse(response);
  },

  async postFormData(endpoint: string, formData: FormData) {
    const response = await fetch(`${API_BASE_URL}${endpoint}`, {
      method: 'POST',
      credentials: 'include',
      body: formData,
    });
    return handleResponse(response);
  },

  async put(endpoint: string, body: any) {
    const response = await fetch(`${API_BASE_URL}${endpoint}`, {
      method: 'PUT',
      credentials: 'include', 
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(body),
    });
    return handleResponse(response);
  },

  async delete(endpoint: string) {
    const response = await fetch(`${API_BASE_URL}${endpoint}`, {
      method: 'DELETE',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
    });
    return handleResponse(response);
  }
};
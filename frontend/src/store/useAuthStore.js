import { create } from 'zustand';
import api from '../lib/axios';

export const useAuthStore = create((set) => ({
  user: null,
  token: typeof window !== 'undefined' ? localStorage.getItem('auth_token') : null,
  isLoading: true,
  login: async (googleToken) => {
    try {
      const { data } = await api.post('/auth/google', { token: googleToken });
      localStorage.setItem('auth_token', data.token);
      set({ user: data.user, token: data.token });
    } catch (error) {
      console.error('Login failed', error);
      throw error;
    }
  },
  logout: async () => {
    try {
      await api.post('/auth/logout');
    } catch (error) {
      console.error('Logout failed', error);
    } finally {
      localStorage.removeItem('auth_token');
      set({ user: null, token: null });
    }
  },
  checkAuth: async () => {
    const token = typeof window !== 'undefined' ? localStorage.getItem('auth_token') : null;
    if (!token) {
      set({ isLoading: false });
      return;
    }
    try {
      const { data } = await api.get('/user');
      set({ user: data, isLoading: false });
    } catch (error) {
      localStorage.removeItem('auth_token');
      set({ user: null, token: null, isLoading: false });
    }
  },
}));

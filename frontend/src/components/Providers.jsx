'use client';

import { GoogleOAuthProvider } from '@react-oauth/google';
import { useAuthStore } from '@/store/useAuthStore';
import { useEffect } from 'react';

export default function Providers({ children }) {
  const checkAuth = useAuthStore((state) => state.checkAuth);

  useEffect(() => {
    checkAuth();
  }, [checkAuth]);

  return (
    <GoogleOAuthProvider clientId={process.env.NEXT_PUBLIC_GOOGLE_CLIENT_ID || ''}>
      {children}
    </GoogleOAuthProvider>
  );
}

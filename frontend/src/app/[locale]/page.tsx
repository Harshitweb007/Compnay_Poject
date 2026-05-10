'use client';

import {useTranslations} from 'next-intl';
import { useAuthStore } from '@/store/useAuthStore';
import { GoogleLogin } from '@react-oauth/google';
import { useRouter } from '@/i18n/routing';
import { useEffect } from 'react';

export default function Home() {
  const t = useTranslations('Index');
  const { user, login, isLoading } = useAuthStore();
  const router = useRouter();

  useEffect(() => {
    if (!isLoading && user) {
      router.push('/dashboard');
    }
  }, [user, isLoading, router]);

  if (isLoading || user) {
    return <div className="min-h-screen flex items-center justify-center">Loading...</div>;
  }

  return (
    <div className="flex flex-col items-center justify-center min-h-screen py-2 bg-slate-50 dark:bg-slate-900">
      <main className="flex flex-col items-center justify-center w-full flex-1 px-20 text-center">
        <h1 className="text-4xl font-bold text-slate-800 dark:text-white mb-6">
          {t('title')}
        </h1>
        
        <div className="bg-white dark:bg-slate-800 p-8 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 flex flex-col items-center gap-4">
          <p className="text-lg text-slate-600 dark:text-slate-300">{t('login')}</p>
          <GoogleLogin
            onSuccess={(credentialResponse) => {
              if (credentialResponse.credential) {
                login(credentialResponse.credential);
              }
            }}
            onError={() => {
              console.log('Login Failed');
            }}
          />
        </div>
      </main>
    </div>
  );
}

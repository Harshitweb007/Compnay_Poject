'use client';

import { useTranslations } from 'next-intl';

export default function ReportsPage() {
  const t = useTranslations('Index');

  return (
    <div>
      <h1 className="text-3xl font-bold text-slate-800 dark:text-white mb-8">
        {t('reports')}
      </h1>
      <div className="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-8 flex items-center justify-center min-h-[400px]">
        <p className="text-slate-500 dark:text-slate-400">Business reports module coming soon.</p>
      </div>
    </div>
  );
}

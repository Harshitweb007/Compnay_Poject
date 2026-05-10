'use client';

import { useTranslations } from 'next-intl';
import { useAuthStore } from '@/store/useAuthStore';
import { FileText, Users, Clock } from 'lucide-react';

export default function DashboardPage() {
  const t = useTranslations('Index');
  const { user } = useAuthStore();

  const stats = [
    { title: 'Total Invoices', value: '142', icon: FileText, color: 'text-blue-600', bg: 'bg-blue-100 dark:bg-blue-900/30' },
    { title: 'Active Employees', value: '24', icon: Users, color: 'text-emerald-600', bg: 'bg-emerald-100 dark:bg-emerald-900/30' },
    { title: 'Present Today', value: '21', icon: Clock, color: 'text-amber-600', bg: 'bg-amber-100 dark:bg-amber-900/30' },
  ];

  return (
    <div>
      <div className="mb-8">
        <h1 className="text-3xl font-bold text-slate-800 dark:text-white">
          {t('welcome')}, {user?.name}
        </h1>
        <p className="text-slate-600 dark:text-slate-400 mt-2">
          Here is your business overview for today.
        </p>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {stats.map((stat, i) => (
          <div key={i} className="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 flex items-center gap-4">
            <div className={`p-4 rounded-lg ${stat.bg}`}>
              <stat.icon className={`w-8 h-8 ${stat.color}`} />
            </div>
            <div>
              <p className="text-sm font-medium text-slate-500 dark:text-slate-400">{stat.title}</p>
              <h3 className="text-2xl font-bold text-slate-800 dark:text-white mt-1">{stat.value}</h3>
            </div>
          </div>
        ))}
      </div>

      {/* Add more dashboard content like recent invoices or attendance overview here */}
    </div>
  );
}

'use client';

import { useTranslations } from 'next-intl';
import { useAuthStore } from '@/store/useAuthStore';
import { Link, usePathname } from '@/i18n/routing';
import { LayoutDashboard, FileText, Users, BarChart3, Mail, LogOut, Menu } from 'lucide-react';
import { useState } from 'react';

export default function Sidebar() {
  const t = useTranslations('Index');
  const pathname = usePathname();
  const { logout, user } = useAuthStore();
  const [isOpen, setIsOpen] = useState(false);

  const navItems = [
    { href: '/dashboard', label: t('dashboard'), icon: LayoutDashboard },
    { href: '/invoices', label: t('invoices'), icon: FileText },
    { href: '/attendance', label: t('attendance'), icon: Users },
    { href: '/reports', label: t('reports'), icon: BarChart3 },
    { href: '/contact', label: t('contact'), icon: Mail },
  ];

  return (
    <>
      <button 
        onClick={() => setIsOpen(!isOpen)} 
        className="md:hidden fixed top-4 left-4 z-50 p-2 bg-white rounded-md shadow-md dark:bg-slate-800"
      >
        <Menu className="w-6 h-6 text-slate-700 dark:text-slate-200" />
      </button>

      <div className={`fixed inset-y-0 left-0 z-40 w-64 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 transform transition-transform duration-300 ease-in-out md:translate-x-0 ${isOpen ? 'translate-x-0' : '-translate-x-full'}`}>
        <div className="flex flex-col h-full">
          <div className="p-6">
            <h1 className="text-2xl font-bold text-slate-800 dark:text-white">GST System</h1>
            <p className="text-sm text-slate-500 dark:text-slate-400 mt-1">{user?.role === 'admin' ? 'Administrator' : 'Staff Member'}</p>
          </div>

          <nav className="flex-1 px-4 space-y-2 overflow-y-auto">
            {navItems.map((item) => {
              const isActive = pathname.startsWith(item.href);
              return (
                <Link
                  key={item.href}
                  href={item.href}
                  className={`flex items-center gap-3 px-4 py-3 rounded-lg transition-colors ${
                    isActive 
                      ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/50 dark:text-blue-400' 
                      : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800'
                  }`}
                  onClick={() => setIsOpen(false)}
                >
                  <item.icon className="w-5 h-5" />
                  <span className="font-medium">{item.label}</span>
                </Link>
              );
            })}
          </nav>

          <div className="p-4 border-t border-slate-200 dark:border-slate-800">
            <button
              onClick={logout}
              className="flex items-center gap-3 w-full px-4 py-3 text-red-600 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
            >
              <LogOut className="w-5 h-5" />
              <span className="font-medium">{t('logout')}</span>
            </button>
          </div>
        </div>
      </div>
    </>
  );
}

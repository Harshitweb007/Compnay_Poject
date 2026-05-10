'use client';

import { useTranslations } from 'next-intl';
import { Plus, Search, Filter } from 'lucide-react';
import { Link } from '@/i18n/routing';

export default function InvoicesPage() {
  const t = useTranslations('Index');

  return (
    <div>
      <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
          <h1 className="text-3xl font-bold text-slate-800 dark:text-white">
            {t('invoices')}
          </h1>
          <p className="text-slate-600 dark:text-slate-400 mt-1">
            Manage and generate GST compliant invoices
          </p>
        </div>
        
        <Link 
          href="/invoices/create"
          className="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm"
        >
          <Plus className="w-5 h-5" />
          <span>Create Invoice</span>
        </Link>
      </div>

      <div className="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div className="p-4 border-b border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row gap-4 justify-between">
          <div className="relative flex-1 max-w-md">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
            <input 
              type="text" 
              placeholder="Search invoices..." 
              className="w-full pl-10 pr-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>
          <button className="flex items-center gap-2 px-4 py-2 text-slate-600 dark:text-slate-300 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition">
            <Filter className="w-5 h-5" />
            <span>Filter</span>
          </button>
        </div>
        
        <div className="overflow-x-auto">
          <table className="w-full text-left border-collapse">
            <thead>
              <tr className="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700 text-sm text-slate-500 dark:text-slate-400">
                <th className="p-4 font-medium">Invoice #</th>
                <th className="p-4 font-medium">Date</th>
                <th className="p-4 font-medium">Firm</th>
                <th className="p-4 font-medium">Amount</th>
                <th className="p-4 font-medium text-right">Actions</th>
              </tr>
            </thead>
            <tbody>
              {/* Placeholder row */}
              <tr className="border-b border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                <td className="p-4 font-medium text-slate-800 dark:text-slate-200">INV-2026-001</td>
                <td className="p-4 text-slate-600 dark:text-slate-400">10 May 2026</td>
                <td className="p-4 text-slate-600 dark:text-slate-400">Acme Corp</td>
                <td className="p-4 font-medium text-slate-800 dark:text-slate-200">₹45,000</td>
                <td className="p-4 text-right">
                  <button className="text-blue-600 dark:text-blue-400 hover:underline text-sm font-medium">View</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}

import api from '@/lib/axios';

/** Download invoice PDF via authenticated API. */
export async function downloadInvoicePdf(invoiceId, filename = 'invoice.pdf') {
  const { data } = await api.get(`/invoices/${invoiceId}/pdf`, {
    responseType: 'blob',
  });
  const blob = new Blob([data], { type: 'application/pdf' });
  const url = window.URL.createObjectURL(blob);
  const anchor = document.createElement('a');
  anchor.href = url;
  anchor.download = filename.endsWith('.pdf') ? filename : `${filename}.pdf`;
  document.body.appendChild(anchor);
  anchor.click();
  anchor.remove();
  window.URL.revokeObjectURL(url);
}

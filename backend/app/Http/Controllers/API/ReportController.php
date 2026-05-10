<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Attendance;

class ReportController extends Controller
{
    public function attendance(Request $request)
    {
        $query = Attendance::with('user');
        
        if ($request->has('month') && $request->has('year')) {
            $query->whereMonth('date', $request->month)
                  ->whereYear('date', $request->year);
        }

        $attendances = $query->get();

        $summary = [
            'total_present' => $attendances->where('status', 'present')->count(),
            'total_absent' => $attendances->where('status', 'absent')->count(),
            'total_half_day' => $attendances->where('status', 'half-day')->count(),
            'total_overtime' => $attendances->sum('overtime_hours'),
            'records' => $attendances
        ];

        return response()->json($summary);
    }

    public function invoices(Request $request)
    {
        $query = Invoice::with('firm');

        if ($request->has('month') && $request->has('year')) {
            $query->whereMonth('date', $request->month)
                  ->whereYear('date', $request->year);
        }

        $invoices = $query->get();

        $summary = [
            'total_count' => $invoices->count(),
            'total_amount' => $invoices->sum('total_amount'),
            'total_cgst' => $invoices->sum('cgst'),
            'total_sgst' => $invoices->sum('sgst'),
            'records' => $invoices
        ];

        return response()->json($summary);
    }
}

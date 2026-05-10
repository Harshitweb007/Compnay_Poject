<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with('user')->orderBy('date', 'desc');
        
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        return response()->json($query->paginate(15));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,half-day',
            'overtime_hours' => 'numeric|min:0',
        ]);

        $attendance = Attendance::updateOrCreate(
            ['user_id' => $request->user_id, 'date' => $request->date],
            ['status' => $request->status, 'overtime_hours' => $request->overtime_hours ?? 0]
        );

        return response()->json(['message' => 'Attendance marked', 'attendance' => $attendance]);
    }
}

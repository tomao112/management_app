<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stamping;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $date = Carbon::today()->toDateString();

        $stamping = Stamping::where('user_id', $userId)
                            ->where('date', $date)
                            ->first();
        
        return view('attendance', ['stamping' => $stamping]);
    }
    
    public function clockIn(Request $request)
    {
        $userId = Auth::id();
        $date = Carbon::today()->toDateString();

        $stamping = Stamping::where('user_id', $userId)
                            ->where('date', $date)
                            ->first();

        if (!$stamping) {
            Stamping::create([
                'user_id' => $userId,
                'date' => $date,
                'clock_in' => Carbon::now(),
            ]);
        }

        return redirect()->route('dashboard');
    }

    public function clockOut(Request $request)
    {
        $userId = Auth::id();
        $date = Carbon::today()->toDateString();

        $stamping = Stamping::where('user_id', $userId)
                            ->where('date', $date)
                            ->first();

        if ($stamping) {
            $stamping->update([
                'clock_out' => Carbon::now(),
            ]);
        }

        return redirect()->route('dashboard');
    }
}

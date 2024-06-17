<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stamping;
use App\Models\BreakTime;
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

    public function startBreak(Request $request)
    {
        $userId = Auth::id();
        $date = Carbon::today()->toDateString();

        $stamping = Stamping::where('user_id', $userId)
                            ->where('date', $date)
                            ->first();

        if ($stamping) {
            BreakTime::create([
                'stamping_id' => $stamping->id,
                'start_time' => Carbon::now(),
            ]);
        }

        return redirect()->route('dashboard');
    }

    public function endBreak(Request $request)
    {
        $userId = Auth::id();
        $date = Carbon::today()->toDateString();

        $stamping = Stamping::where('user_id', $userId)
                            ->where('date', $date)
                            ->first();

        if ($stamping) {
            $lastBreak = BreakTime::where('stamping_id', $stamping->id)
                                ->latest()
                                ->first();

            if ($lastBreak && !$lastBreak->end_time) {
                $lastBreak->update([
                    'end_time' => Carbon::now(),
                ]);
            }
        }

        return redirect()->route('dashboard');
    }

    public function showAttendance()
    {
        $userId = Auth::id();
        $stampings = Stamping::where('user_id', $userId)->orderBy('date', 'desc')->get();

        $stampingsWithBreakTime = $stampings->map(function ($stamping) use ($userId) {
            $breakTimes = $this->getBreakTime($userId, $stamping->date);
            $stamping['totalBreakTime'] = $breakTimes['totalBreakTime'] ?? 'N/A';
            $stamping['workDuration'] = $breakTimes['workDuration'] ?? 'N/A';
            return $stamping;
        });

        return view('attendance', compact('stampingsWithBreakTime'));
    }

    public function getBreakTime($userId, $date)
    {
        $stamping = Stamping::where('user_id', $userId)
                            ->where('date', $date)
                            ->first();
        
        if ($stamping) {
            $clockIn = $stamping->clock_in;
            $clockOut = $stamping->clock_out;

            $breaks = BreakTime::where('stamping_id', $stamping->id)->get();

            $totalBreakTime = 0;

            foreach ($breaks as $break) {
                if ($break->start_time && $break->end_time) {
                    $startTime = Carbon::parse($break->start_time);
                    $endTime = Carbon::parse($break->end_time);
                    $breakDuration = $endTime->diffInMinutes($startTime);
                    $totalBreakTime += $breakDuration;
                }
            }

            $workDuration = Carbon::parse($clockOut)->diffInMinutes($clockIn) - $totalBreakTime;

            return [
                'clockIn' => $clockIn,
                'clockOut' => $clockOut,
                'totalBreakTime' => $this->formatTime($totalBreakTime),
                'workDuration' => $this->formatTime($workDuration),
            ];
        }
        return null;
    }

    private function formatTime($minutes)
    {
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        return sprintf("%d時間%d分", $hours, $remainingMinutes);
    }
}

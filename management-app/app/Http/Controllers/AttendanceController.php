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
        $userId = Auth::id(); //ログインしているユーザーのIDを取得
        $date = Carbon::today()->toDateString(); //今日の日付をYYYY-MM-DD形式で表示

        //今日の日付とユーザーIDに一致する記録が既に存在するか確認
        $stamping = Stamping::where('user_id', $userId)
                            ->where('date', $date)
                            ->first();

            // 出勤履歴がない場合にのみ出勤記録を作成する
        if (!$stamping) {
            Stamping::create([
                'user_id' => $userId,
                'date' => $date,
                'clock_in' => Carbon::now(),
            ]);
        }

        // 休憩開始時刻を記録
        BreakTime::create([
            'stamping_id' => $stamping->id,
            'start_time' => Carbon::now(),
        ]);

        return redirect()->route('dashboard');
    }

    public function clockOut(Request $request)
    {
        $userId = Auth::id(); //ログインしているユーザーのIDを取得
        $date = Carbon::today()->toDateString(); //今日の日付をYYYY-MM-DD形式で取得

        // 今日の日付とユーザーIDに一致する記録を取得
        $stamping = Stamping::where('user_id', $userId)
                            ->where('date', $date)
                            ->first();

        // 退勤履歴がない場合にのみ退勤時刻を更新
        if ($stamping) {
            $stamping->update([
                'clock_out' => Carbon::now(),
            ]);
        }

        // 最後の休憩レコードを取得し、終了時間を更新
        $lastBreak = BreakTime::where('stamping_id', $stamping->id)
                            ->latest()
                            ->first();
        if($lastBreak) {
            $lastBreak->update([
                'end_time' => Carbon::now(),
            ]);
        }
        return redirect()->route('dashboard');
    }

    public function showAttendance()
{
    $userId = Auth::id(); //ログインしているユーザーのIDを取得
    $stampings = Stamping::where('user_id', $userId)->orderBy('date', 'desc')->get(); //ユーザーの打刻時間を降順で表示させる

        // それぞれのスタンピングに対して休憩時間を取得する
        $stampingsWithBreakTime = $stampings->map(function ($stamping) {
            return array_merge(
                $stamping->toArray(),
                $this->getBreakTime($stamping->user_id, $stamping->date)
            );
        });
    
        return view('attendance', compact('stampingsWithBreakTime', 'stampings'));
}

    //休憩時間の取得メソッド 
    public function getBreakTime($userId, $date)
    {
        $stamping = Stamping::where('user_id', $userId)
                            ->where('date', $date)
                            ->first();
        
        if($stamping) {
            // 出勤時間と退勤時間を取得
            $clockIn = $stamping->clock_in;
            $clockOut = $stamping->clock_out;

            // 休憩時間を取得
            $breaks = BreakTime::where('stamping_id', $stamping->id)
                            ->get();

            $totalBreakTime = 0;

            foreach($breaks as $break) {
                $startTime = Carbon::parse($break->start_time);
                $endTime = Carbon::parse($break->end_time);
                $breakDuration = $endTime->diffInMinutes($startTime);
                $totalBreakTime += $breakDuration;
            }

            // 出勤時間から退勤時間と休憩時間を差し引いた実働時間を計算
            $workDuration = Carbon::parse($clockOut)->diffInMinutes($clockIn) - $totalBreakTime;

            return [
                'clockIn' => $clockIn,
                'clockOut' => $clockOut,
                'totalBreakTime' => $totalBreakTime,
                'workDuration' => $workDuration,
            ];
        }
        return null;
    }

    public function startBreak(Request $request)
    {
        $userId = Auth::id();
        $date = Carbon::today()->toDateString();

        // 今日の日付とユーザーIDに一致する打刻を取得
        $stamping = Stamping::where('user_id', $userId)
                            ->where('date', $date)
                            ->first();

         // 打刻が存在する場合のみ休憩を開始
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

        // 今日の日付とユーザーIDに一致する打刻を取得
        $stamping = Stamping::where('user_id', $userId)
                            ->where('date', $date)
                            ->first();

        // 最後の休憩を取得して終了時刻を記録
        if ($stamping) {
            $lastBreak = BreakTime::where('stamping_id', $stamping->id)
                                ->whereNull('end_time')
                                ->latest()
                                ->first();

            if ($lastBreak) {
                $lastBreak->update([
                    'end_time' => Carbon::now(),
                ]);
            }
        }

        return redirect()->route('dashboard');
    }
}

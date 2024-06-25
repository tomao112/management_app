<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stamping;
use App\Models\BreakTime;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // ユーザーの当日の出退勤時間を取得し、表示
    public function index()
    {
        $userId = Auth::id(); //ログイン中のユーザーのIDを取得
        $date = Carbon::today()->toDateString(); //現在の日付を取得し、文字列に変換

        // Stampingモデルから指定したユーザーIDと日付で条件を絞り、最初に見つかった出退勤情報を取得
        $stamping = Stamping::where('user_id', $userId)
                            ->where('date', $date)
                            ->first();

        return view('attendance', ['stamping' => $stamping]);
    }

    // 出勤記録
    public function clockIn(Request $request)
    {
        $userId = Auth::id();
        $date = Carbon::today()->toDateString();

        $stamping = Stamping::where('user_id', $userId)
                            ->where('date', $date)
                            ->first();

        //出退勤情報がなければ、新たにレコードを作成し、出勤時間を現在時刻で記録 
        if (!$stamping) {
            Stamping::create([
                'user_id' => $userId,
                'date' => $date,
                'clock_in' => Carbon::now(),
            ]);
        }

        // ログを追加
        \Log::info('Clock In:', ['user_id' => $userId, 'date' => $date, 'clock_in' => Carbon::now()]);

        return redirect()->route('dashboard');
    }

    public function clockOut(Request $request)
    {
        $userId = Auth::id();
        $date = Carbon::today()->toDateString();

        $stamping = Stamping::where('user_id', $userId)
                            ->where('date', $date)
                            ->first();

        //出退勤情報が存在すれば、そのレコードの'clock_out'を現在時刻で更新
        if ($stamping) {
            $stamping->update([
                'clock_out' => Carbon::now(),
            ]);

            // ログを追加
            \Log::info('Clock Out:', ['user_id' => $userId, 'date' => $date, 'clock_out' => Carbon::now()]);
        }

        return redirect()->route('dashboard');
    }

    // 休憩の開始を記録
    public function startBreak(Request $request)
    {
        $userId = Auth::id();
        $date = Carbon::today()->toDateString();

        $stamping = Stamping::where('user_id', $userId)
                            ->where('date', $date)
                            ->first();

        // 出退勤情報が存在すれば、そのレコードID使ってBreakTimeモデルに休憩の開始時間'start_time'を記録
        if ($stamping) {
            BreakTime::create([
                'stamping_id' => $stamping->id,
                'start_time' => Carbon::now(),
            ]);
        }

        return redirect()->route('dashboard');
    }

    // 休憩の終了を記録
    public function endBreak(Request $request)
    {
        $userId = Auth::id();
        $date = Carbon::today()->toDateString();

        $stamping = Stamping::where('user_id', $userId)
                            ->where('date', $date)
                            ->first();

        // 出退勤情報が存在すれば、そのレコードIDを使ってBreakTimeモデルから最後に記録された休憩情報を取得
        if ($stamping) {
            $lastBreak = BreakTime::where('stamping_id', $stamping->id)
                                ->latest()
                                ->first();
            // 最後に記録された休憩所法が存在し、かつ終了時間'end_time'が記録されていない場合に、現在時刻で'end_time'を更新
            if ($lastBreak && !$lastBreak->end_time) {
                $lastBreak->update([
                    'end_time' => Carbon::now(),
                ]);
            }
        }

        return redirect()->route('dashboard');
    }

    public function getBreakStatus()
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
                return response()->json(['onBreak' => true]);
            }
        }

        return response()->json(['onBreak' => false]);
    }

    // ユーザーの過去の出退勤情報と休憩時間の一覧を表示
    public function showAttendance()
    {
        $userId = Auth::id();
        $stampings = Stamping::where('user_id', $userId)->orderBy('date', 'desc')->get(); //指定したユーザーIDで条件を絞り、日付で降順に並べ替え

        // 取得した出退勤情報それぞれに対して、getBreakTimeメソッドを呼び出して休憩時間を計算
        // totalBreakTimeとworkDurationを追加した新しいコレクションを作成(mapメソッド使用)
        $stampingsWithBreakTime = $stampings->map(function ($stamping) use ($userId) {
            $breakTimes = $this->getBreakTime($userId, $stamping->date);
            $stamping['totalBreakTime'] = $breakTimes['totalBreakTime'] ?? 'N/A';
            $stamping['workDuration'] = $breakTimes['workDuration'] ?? 'N/A';
            return $stamping;
        });

        return view('attendance', compact('stampingsWithBreakTime'));
    }

    // 指定したユーザーと日付に関連する休憩時間と実働時間を計算
    public function getBreakTime($userId, $date)
    {
        $stamping = Stamping::where('user_id', $userId)
                            ->where('date', $date)
                            ->first();
    
        // 出退勤情報が存在すれば、それに関連する休憩情報を取得し、各休憩の開始時間と終了時間を使って休憩時間(totalBreakTime)を計算
        if ($stamping) {
            // デフォルトタイムゾーンを使用
            $clockIn = Carbon::parse($stamping->clock_in, 'Asia/Tokyo');
            $clockOut = $stamping->clock_out ? Carbon::parse($stamping->clock_out, 'Asia/Tokyo') : null;
    
            // ログを追加
            \Log::info('Clock In and Out:', [
                'clock_in' => $stamping->clock_in,
                'clock_out' => $stamping->clock_out
            ]);
    
            $breaks = BreakTime::where('stamping_id', $stamping->id)->get();
    
            $totalBreakTime = 0;
    
            foreach ($breaks as $break) {
                if ($break->start_time && $break->end_time) {
                    $startTime = Carbon::parse($break->start_time, 'Asia/Tokyo');
                    $endTime = Carbon::parse($break->end_time, 'Asia/Tokyo');
                    $breakDuration = $endTime->diffInMinutes($startTime);
                    $totalBreakTime += $breakDuration;
                }
            }
    
            // 実働時間を計算する（退勤時間から出勤時間を引く）
            if ($clockOut) {
                $workDuration = $clockIn->diffInMinutes($clockOut);
            } else {
                $workDuration = 0;
            }
    
            // ログを追加
            \Log::info('Break Time Calculation:', [
                'user_id' => $userId,
                'date' => $date,
                'clock_in' => $clockIn,
                'clock_out' => $clockOut,
                'totalBreakTime' => $totalBreakTime,
                'diffInMinutes' => $diffInMinutes ?? 'N/A',
                'workDuration' => $workDuration
            ]);
    
            // 計算結果を連想配列で返す
            return [
                'clockIn' => $clockIn->format('H:i'),
                'clockOut' => $clockOut ? $clockOut->format('H:i') : '-',
                'totalBreakTime' => $this->formatTime($totalBreakTime),
                'workDuration' => $clockOut ? $this->formatTime($workDuration) : '-',
            ];
        }
        return null;
    }
    // Carbon::now()
    // 現在の日時を取得するために使用。出退勤情報や休憩時間の記録に利用

    // Carbon::today()->toDateString()
    // 現在の日付を取得し、文字列形式に変換して出退勤記録の検索条件に使用。

    // Carbon::parse($timestamp, 'Asia/Tokyo')
    // 日本時間で時刻を解析するために使用。

    // diffInMinutes()
    // 2つの日時の差分を分単位で計算するために使用。


    // 正しい時間形式に変換
    private function formatTime($minutes)
    {
        $hours = intdiv($minutes, 60);
        $minutes = $minutes % 60;
        return sprintf('%02d:%02d', $hours, $minutes);
    }
}

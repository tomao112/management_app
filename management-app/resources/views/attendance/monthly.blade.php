<!-- resources/views/attendance/monthly.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <title>月別勤務時間</title>
</head>

<body>
    <h1>{{ $year }}年{{ $month }}月の勤務時間</h1>
    <h2>合計勤務時間: {{ $totalHours }}</h2>

    <h3>月ごとのリンク</h3>
    <ul>
        @foreach ($months as $monthLink)
            <li>
                <a href="{{ $monthLink['link'] }}">{{ $monthLink['year'] }}年{{ $monthLink['month'] }}月</a>
            </li>
        @endforeach
    </ul>

    <h3>日ごとの勤務時間</h3>
    <table border="1">
        <thead>
            <tr>
                <th>日付</th>
                <th>勤務時間</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dailyHours as $date => $hours)
                <tr>
                    <td>{{ $date }}</td>
                    <td>{{ $hours }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>

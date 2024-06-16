<x-app-layout>
    <x-slot name="header">
        <h2 class="text-center text-2xl font-bold">
            {{ Auth::user()->name }} の勤務時間一覧
        </h2>
    </x-slot>
    <div class="flex justify-center">
        <div class="w-full max-w-4xl">
            @if ($stampings->isEmpty())
                <p class="text-center mt-4">勤怠情報はありません。</p>
            @else
                <table class="min-w-full bg-white border-collapse border border-gray-200 shadow-lg">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 px-4 py-2 text-gray-800">日付</th>
                            <th class="border border-gray-300 px-4 py-2 text-gray-800">出勤時間</th>
                            <th class="border border-gray-300 px-4 py-2 text-gray-800">退勤時間</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($stampings as $stamping)
                            <tr class="hover:bg-gray-100">
                                <td class="border border-gray-300 px-4 py-2 text-center">{{ $stamping->date }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-center">{{ $stamping->clock_in }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-center">{{ $stamping->clock_out }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @foreach ($stampingsWithBreakTime as $stamping)
                    <div>
                        <p>日付: {{ $stamping['date'] }}</p>
                        <p>出勤時間: {{ $stamping['clock_in'] }}</p>
                        <p>退勤時間: {{ $stamping['clock_out'] }}</p>
                        <p>休憩時間: {{ $stamping['totalBreakTime'] }} 分</p>
                        <p>実働時間: {{ $stamping['workDuration'] }} 分</p>
                    </div>
                    <hr>
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>

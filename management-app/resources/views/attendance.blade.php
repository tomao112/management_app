<x-app-layout>
    <x-slot name="header">
        <h2 class="text-center text-2xl font-bold">
            {{ Auth::user()->name }} の勤務時間一覧
        </h2>
    </x-slot>
    <div class="flex justify-center">
        <div class="w-full max-w-4xl">
            @if ($stampingsWithBreakTime->isEmpty())
                <p class="text-center mt-4">勤怠情報はありません。</p>
            @else
                <table class="min-w-full bg-white border-collapse border border-gray-200 shadow-lg">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 px-4 py-2 text-gray-800">日付</th>
                            <th class="border border-gray-300 px-4 py-2 text-gray-800">出勤時間</th>
                            <th class="border border-gray-300 px-4 py-2 text-gray-800">退勤時間</th>
                            <th class="border border-gray-300 px-4 py-2 text-gray-800">休憩時間</th>
                            <th class="border border-gray-300 px-4 py-2 text-gray-800">実働時間</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($stampingsWithBreakTime as $stamping)
                            <tr class="hover:bg-gray-100">
                                <td class="border border-gray-300 px-4 py-2 text-center">{{ $stamping->date }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-center">{{ $stamping->clock_in }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-center">{{ $stamping->clock_out }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-center">{{ $stamping->totalBreakTime }}
                                </td>
                                <td class="border border-gray-300 px-4 py-2 text-center">{{ $stamping->workDuration }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
    <div class="flex justify-center mt-4">
        <form method="POST" action="{{ route('start.break') }}">
            @csrf
            <button type="submit" class="btn btn-primary">休憩開始</button>
        </form>
        <form method="POST" action="{{ route('end.break') }}" class="ml-2">
            @csrf
            <button type="submit" class="btn btn-primary">休憩終了</button>
        </form>
    </div>
</x-app-layout>

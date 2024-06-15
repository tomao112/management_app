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
                @foreach ($stampings as $stamping)
                    <div class="mb-4 p-4 border rounded-lg shadow">
                        <p>日付: {{ $stamping->date }}</p>
                        <p>出勤時間: {{ $stamping->clock_in }}</p>
                        <p>退勤時間: {{ $stamping->clock_out }}</p>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>

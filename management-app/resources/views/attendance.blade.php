<x-app-layout>
    {{-- <x-slot name="header">
        <h2>
            {{ Auth::user()->name }}
        </h2>
    </x-slot> --}}
    <div>
        @if ($stamping)
            <p>日付: {{ $stamping->date }}</p>
            <p>出勤時間: {{ $stamping->clock_in }}</p>
            <p>退勤時間: {{ $stamping->clock_out }}</p>
        @else
            <p>本日の勤怠情報はありません。</p>
        @endif
    </div>
</x-app-layout>

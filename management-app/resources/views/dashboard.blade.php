<x-app-layout>
    {{-- <div class="flex flex-col items-center justify-center h-screen"> --}}
    <x-realtime />
    <div class="flex">
        <x-clockin-button />
        <x-clockout-button />
    </div>
    <!-- 休憩開始ボタン -->
    <form method="POST" action="{{ route('start.break') }}">
        @csrf
        <button type="submit" class="btn btn-primary">休憩開始</button>
    </form>
    <!-- 休憩終了ボタン -->
    <form method="POST" action="{{ route('end.break') }}">
        @csrf
        <button type="submit" class="btn btn-danger">休憩終了</button>
    </form>
    </div>
    </div>

</x-app-layout>

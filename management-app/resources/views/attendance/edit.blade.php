<x-app-layout>
    <x-slot name="header">
        <h2 class="text-center text-2xl font-bold">
            勤務時間の修正
        </h2>
    </x-slot>
    <div class="flex justify-center">
        <div class="w-full max-w-2xl">
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">エラー!</strong>
                    <span class="block sm:inline">{{ $errors->first() }}</span>
                </div>
            @endif
            <form method="POST" action="{{ route('attendance.update', $stamping->id) }}">
                @csrf
                <div class="mb-4">
                    <label for="clock_in" class="block text-gray-700 font-bold mb-2">出勤時間</label>
                    <input type="time" id="clock_in" name="clock_in"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('clock_in') border-red-500 @enderror"
                        value="{{ old('clock_in', \Carbon\Carbon::parse($stamping->clock_in)->format('H:i')) }}"
                        required>
                    @error('clock_in')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="clock_out" class="block text-gray-700 font-bold mb-2">退勤時間</label>
                    <input type="time" id="clock_out" name="clock_out"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('clock_out') border-red-500 @enderror"
                        value="{{ old('clock_out', $stamping->clock_out ? \Carbon\Carbon::parse($stamping->clock_out)->format('H:i') : '') }}">
                    @error('clock_out')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        更新
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

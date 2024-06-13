<form method="POST" action="{{ route('clock.in') }}">
    @csrf
    <button type="submit">出勤</button>
</form>

<style></style>

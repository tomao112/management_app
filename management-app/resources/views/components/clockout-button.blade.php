<form method="POST" action="{{ route('clock.out') }}">
    @csrf
    <button type="submit">退勤</button>
</form>

<style></style>

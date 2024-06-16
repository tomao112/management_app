<form method="POST" action="{{ route('clock.out') }}">
    @csrf
    <button class="btn btn-circle">退勤</button>
</form>

<style>
    .btn,
    a.btn,
    button.btn {
        margin-left: 1rem;
        font-size: 1.6rem;
        line-height: 1.5;
        cursor: pointer;
        user-select: none;
        text-align: center;
        vertical-align: middle;
        text-decoration: none;
        letter-spacing: 0.1em;
        color: #eb6100;
        border: 2px solid #eb6100;
        border-radius: 50%;
        width: 130px !important;
        height: 130px !important;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: transparent;
    }

    .btn:hover {
        border-color: #d35400;
        color: #d35400;
    }
</style>

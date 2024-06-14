<form method="POST" action="{{ route('clock.in') }}">
    @csrf
    <button class="btn btn-malformation">出勤</button>
</form>

<style>
    *,
    *:before,
    *:after {
        -webkit-box-sizing: inherit;
        box-sizing: inherit;
    }

    html {
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        font-size: 62.5%;
    }

    .btn,
    a.btn,
    button.btn {
        font-size: 1.6rem;
        font-weight: 700;
        line-height: 1.5;
        position: relative;
        display: inline-block;
        padding: 1rem 4rem;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        -webkit-transition: all 0.3s;
        transition: all 0.3s;
        text-align: center;
        vertical-align: middle;
        text-decoration: none;
        letter-spacing: 0.1em;
        color: #212529;
        border-radius: 0.5rem;
    }

    a.btn-malformation {
        font-size: 2rem;

        padding: 3rem 4rem;

        color: #fff;
        border-radius: 100% 80px / 80px 100%;
        background-color: #eb6100;
    }

    a.btn-malformation:hover {
        color: #fff;
        border-radius: 60% 80% / 100% 80%;
    }
</style>

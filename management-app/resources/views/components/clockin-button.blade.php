<form class="btn_form" method="POST" action="{{ route('clock.in') }}">
    @csrf
    <button class="shadow_btn01"><span>出勤</span></button>
</form>

<style>
    button.shadow_btn01 {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        max-width: 350px;
        height: 3.5em;
        background: transparent;
        /* 背景を透明に設定 */
        position: relative;
        border: none;
        padding: 0;
        cursor: pointer;
        text-decoration: none;
        outline: none;
    }

    button.shadow_btn01 span {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
        color: #000;
        font-weight: bold;
        letter-spacing: 0.1em;
        text-decoration: none;
        box-shadow: 0px 5px 12px #cad4e2, -6px -6px 12px #fff;
        border-radius: 10px;
        position: absolute;
        top: 0;
        left: 0;
        transition-duration: 0.2s;
        z-index: 1;
        /* スパン要素が親要素（ボタン）の背景より前面に表示されるように */
    }

    button.shadow_btn01:hover span {
        box-shadow: 0 0 4px #CAD4E2, -2px -2px 4px #FFF;
    }

    .btn_form {
        width: 100%;
        max-width: 250px;
    }
</style>

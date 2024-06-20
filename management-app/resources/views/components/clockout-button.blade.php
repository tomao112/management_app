<form class="btn_form" method="POST" action="{{ route('clock.out') }}">
    @csrf
    <button class="shadow_btn01"><span>退勤</span></button>
</form>

<style>
    /* 共通スタイルの設定 */
    button.shadow_btn01 {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        max-width: 250px;
        height: 3.5em;
        background: #fff;
        position: relative;
        border: none;
        /* ボタンのデフォルトのボーダーを削除 */
        padding: 0;
        /* ボタンのデフォルトのパディングを削除 */
        cursor: pointer;
        /* マウスオーバー時のカーソルをポインターに */
        text-decoration: none;
        outline: none;
    }

    /* 共通スタイルの設定（ホバー効果） */
    button.shadow_btn01:hover {
        text-decoration: none;
        /* ホバー時にテキストデコレーションを消す */
    }

    button.shadow_btn01 span {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
        /* 親要素の高さに合わせる */
        background: #fff;
        /* 背景色 */
        color: #000;
        /* 文字色 */
        font-weight: bold;
        /* 文字の太さ */
        letter-spacing: 0.1em;
        text-decoration: none;
        box-shadow: 0px 5px 12px #cad4e2, -6px -6px 12px #fff;
        border-radius: 10px;
        position: absolute;
        top: -5px;
        left: 0;
        transition-duration: 0.2s;
    }

    /* マウスオーバーした際のデザイン */
    button.shadow_btn01:hover span {
        left: 0;
        top: 0;
        box-shadow: 0 0 4px #CAD4E2, -2px -2px 4px #FFF;
    }

    .btn_form {
        width: 100%;
        max-width: 250px;
    }
</style>

<!-- 休憩開始ボタン -->
<form class="btn_form" method="POST" action="{{ route('start.break') }}">
    @csrf
    <button id="start-break-button" class="shadow_btn01" onclick="submitStartBreak(event)" disabled>
        <span>休憩開始</span>
    </button>
</form>

<!-- 休憩終了ボタン -->
<form class="btn_form" method="POST" action="{{ route('end.break') }}">
    @csrf
    <button id="end-break-button" class="shadow_btn01" onclick="submitEndBreak(event)">
        <span>休憩終了</span>
    </button>
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
        position: relative;
        border: none;
        padding: 0;
        cursor: pointer;
        text-decoration: none;
        outline: none;
    }

    button.shadow_btn01:hover {
        text-decoration: none;
    }

    button.shadow_btn01 span {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
        background: #fff;
        color: #000;
        font-weight: bold;
        letter-spacing: 0.1em;
        text-decoration: none;
        box-shadow: 0px 5px 12px #cad4e2, -6px -6px 12px #fff;
        border-radius: 10px;
        position: absolute;
        top: -5px;
        left: 0;
        transition-duration: 0.2s;
    }

    button.shadow_btn01:hover span {
        left: 0;
        top: 0;
        box-shadow: 0 0 4px #CAD4E2, -2px -2px 4px #FFF;
    }

    button.shadow_btn01[disabled] span {
        color: #aaa;
        background-color: #f0f0f0;
        /* グレーアウトの背景色 */
        box-shadow: 0px 5px 12px #cad4e2, -6px -6px 12px #fff;
        /* グレーアウトの影 */
        cursor: not-allowed;
    }

    .btn_form {
        width: 100%;
        max-width: 250px;
    }
</style>

<!-- JavaScript -->
<script>
    // ページ読み込み時にfetchBreakStatusを呼び出す
    document.addEventListener('DOMContentLoaded', function() {
        fetchBreakStatus();
    });

    function fetchBreakStatus() {
        fetch("{{ route('break.status') }}")
            .then(response => response.json())
            .then(data => {
                var startBreakButton = document.getElementById('start-break-button');
                var endBreakButton = document.getElementById('end-break-button');

                // 休憩状態に応じてボタンの表示・非表示と有効・無効を切り替える
                if (data.onBreak) {
                    startBreakButton.disabled = true;
                    endBreakButton.disabled = false;
                } else {
                    startBreakButton.disabled = false;
                    endBreakButton.disabled = true;
                }
            })
            .catch(error => console.error('Error fetching break status:', error));
    }

    // 休憩開始ボタンクリック時の処理
    function submitStartBreak(event) {
        event.preventDefault();
        var button = document.getElementById('start-break-button');
        var form = button.closest('form');

        button.disabled = true;

        form.submit();
    }

    // 休憩終了ボタンクリック時の処理
    function submitEndBreak(event) {
        event.preventDefault();
        var button = document.getElementById('end-break-button');
        var form = button.closest('form');

        button.disabled = true;

        form.submit();
    }
</script>

<!-- Bladeテンプレート内のHTML -->
<form class="btn_form" method="POST" action="{{ route('clock.out') }}">
    @csrf
    <button id="clock-out-button" class="shadow_btn01" onclick="submitClockOut(event)">
        <span>退勤</span>
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

    button.shadow_btn01:disabled span {
        color: #aaa;
        box-shadow: none;
        cursor: not-allowed;
    }

    .btn_form {
        width: 100%;
        max-width: 250px;
    }
</style>

<!-- JavaScript -->
<script>
    // ページ読み込み時にfetchAttendanceStatusを呼び出す
    document.addEventListener('DOMContentLoaded', function() {
        fetchAttendanceStatus();
    });

    function fetchAttendanceStatus() {
        fetch("{{ route('attendance.status') }}")
            .then(response => response.json())
            .then(data => {
                var clockInButton = document.getElementById('clock-in-button');
                var clockOutButton = document.getElementById('clock-out-button');

                // 出退勤ボタン無効化の条件式
                if (data.clockIn && !data.clockOut) {
                    clockInButton.disabled = true;
                    clockOutButton.disabled = false;
                } else if (data.clockIn && data.clockOut) {
                    clockInButton.disabled = true;
                    clockOutButton.disabled = true;
                } else {
                    clockInButton.disabled = false;
                    clockOutButton.disabled = true;
                }
            })
            .catch(error => console.error('Error fetching attendance status:', error));
    }

    // 出退勤ボタンのフォーム送信を防止する関数
    function preventFormSubmit(event) {
        event.preventDefault();
        return false;
    }

    // 退勤ボタンクリック時の処理
    function submitClockOut(event) {
        event.preventDefault(); // フォームのデフォルトの送信をキャンセル
        var button = document.getElementById('clock-out-button');
        var form = button.closest('form');

        // ボタンを無効化
        button.disabled = true;

        // フォームの送信
        form.submit();
    }
</script>

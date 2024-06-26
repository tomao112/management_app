<!-- Bladeテンプレート内のHTML -->
<form class="btn_form" method="POST" action="{{ route('clock.in') }}">
    @csrf
    <button id="clock-in-button" class="shadow_btn01"><span>出勤</span></button>
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
    }

    button.shadow_btn01:hover span {
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

    // 
    function fetchAttendanceStatus() {
        fetch("{{ route('attendance.status') }}") //fetchを使用してサーバーから出退勤情報を取得
            .then(response => response.json()) //json形式で変換
            .then(data => {
                var clockInButton = document.getElementById('clock-in-button');

                // 出退勤ボタン無効化の条件式
                if (data.clockIn && !data.clockOut) {
                    clockInButton.disabled = true;
                } else {
                    clockInButton.disabled = false;
                }
            })
            // エラーが発生した場合にコンソールにエラーメッセージを表示
            .catch(error => console.error('Error fetching attendance status:', error));
    }

    document.getElementById('clock-in-button').addEventListener('click', function(event) {
        event.preventDefault(); // デフォルトのフォームサブミットをキャンセル
        var button = this;
        var form = button.closest('form');

        // ボタンをグレーアウトして無効化
        button.disabled = true;

        form.submit();
    });
</script>

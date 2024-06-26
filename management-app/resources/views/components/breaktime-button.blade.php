<!-- Bladeテンプレート内のHTML -->
<form class="btn_form" id="break-form" method="POST">
    @csrf
    <button id="break-button" class="shadow_btn01" onclick="toggleBreakButton(event)">
        <span id="break-button-text">休憩開始</span>
    </button>
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

<!-- JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetchAttendanceAndBreakStatus();
    });

    function fetchAttendanceAndBreakStatus() {
        // 出退勤状態を取得
        fetch("{{ route('attendance.status') }}")
            .then(response => response.json())
            .then(attendanceData => {
                var clockInButton = document.getElementById('clock-in-button');
                var clockOutButton = document.getElementById('clock-out-button');
                var breakButton = document.getElementById('break-button');
                var breakButtonText = document.getElementById('break-button-text');

                if (attendanceData.clockIn && !attendanceData.clockOut) {
                    clockInButton.disabled = true;
                    clockOutButton.disabled = false;
                    breakButton.disabled = false;
                } else if (attendanceData.clockIn && attendanceData.clockOut) {
                    clockInButton.disabled = true;
                    clockOutButton.disabled = true;
                    breakButton.disabled = true;
                } else {
                    clockInButton.disabled = false;
                    clockOutButton.disabled = true;
                    breakButton.disabled = true;
                }

                // 休憩状態を取得
                return fetch("{{ route('break.status') }}");
            })
            .then(response => response.json())
            .then(breakData => {
                if (breakData.onBreak) {
                    breakButtonText.textContent = '休憩終了';
                    if (!clockOutButton.disabled) {
                        breakButton.disabled = false;
                    }
                } else {
                    breakButtonText.textContent = '休憩開始';
                }
            })
            .catch(error => console.error('Error fetching attendance and break status:', error));
    }

    function toggleBreakButton(event) {
        event.preventDefault(); // デフォルトのフォームサブミットをキャンセル

        var buttonText = document.getElementById('break-button-text');
        var form = document.getElementById('break-form');

        if (buttonText.textContent.trim() === '休憩開始') {
            // 休憩開始ボタンを押したときの処理
            form.action = "{{ route('start.break') }}"; // 休憩開始のルートに設定
        } else {
            // 休憩終了ボタンを押したときの処理
            form.action = "{{ route('end.break') }}"; // 休憩終了のルートに設定
        }

        // フォームのサブミットを実行
        form.submit();
    }
</script>

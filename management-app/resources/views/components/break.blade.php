<!-- 休憩開始ボタンのフォーム -->
<form class="btn_form" id="start-break-form" method="POST">
    @csrf
    <button id="start-break-button" class="shadow_btn01">
        <span id="start-break-button-text">休憩開始</span>
    </button>
</form>

<!-- 休憩終了ボタンのフォーム -->
<form class="btn_form" id="end-break-form" method="POST">
    @csrf
    <button id="end-break-button" class="shadow_btn01" style="display:none;">
        <span id="end-break-button-text">休憩終了</span>
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


<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetchAttendanceAndBreakStatus();

        document.getElementById('start-break-button').addEventListener('click', function(event) {
            event.preventDefault();
            var form = document.getElementById('start-break-form');
            form.action = "{{ route('start.break') }}";
            form.submit();
        });

        document.getElementById('end-break-button').addEventListener('click', function(event) {
            event.preventDefault();
            var form = document.getElementById('end-break-form');
            form.action = "{{ route('end.break') }}";
            form.submit();
        });
    });

    function fetchAttendanceAndBreakStatus() {
        fetch("{{ route('attendance.status') }}")
            .then(response => response.json())
            .then(attendanceData => {
                var startBreakButton = document.getElementById('start-break-button');
                var endBreakButton = document.getElementById('end-break-button');

                if (!attendanceData.clockIn) {
                    startBreakButton.disabled = true;
                    endBreakButton.disabled = true;
                } else if (attendanceData.clockIn && !attendanceData.clockOut) {
                    startBreakButton.disabled = false;
                    endBreakButton.disabled = true;
                } else if (attendanceData.clockIn && attendanceData.clockOut) {
                    startBreakButton.disabled = true;
                    endBreakButton.disabled = true;
                }

                return fetch("{{ route('break.status') }}");
            })
            .then(response => response.json())
            .then(breakData => {
                var startBreakButton = document.getElementById('start-break-button');
                var endBreakButton = document.getElementById('end-break-button');

                if (breakData.onBreak) {
                    startBreakButton.style.display = 'none';
                    endBreakButton.style.display = 'block';
                    endBreakButton.disabled = false; // ここを追加
                } else {
                    startBreakButton.style.display = 'block';
                    endBreakButton.style.display = 'none';
                }
            })
            .catch(error => console.error('Error fetching attendance and break status:', error));
    }
</script>

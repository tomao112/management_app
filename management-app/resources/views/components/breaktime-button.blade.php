<!-- Bladeテンプレート内のHTML -->
<form id="break-form" method="POST">
    @csrf
    <button id="break-button" class="btn btn-primary" onclick="toggleBreakButton(event)">
        休憩開始
    </button>
</form>

<!-- JavaScript -->
<script>
    function toggleBreakButton(event) {
        event.preventDefault(); // デフォルトのフォームサブミットをキャンセル

        var button = document.getElementById('break-button');
        var form = document.getElementById('break-form');

        if (button.textContent.trim() === '休憩開始') {
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

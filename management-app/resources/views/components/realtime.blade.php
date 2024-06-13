<!-- Bladeテンプレート内のHTML -->
<p class="time" id="current-time">{{ now()->format('Y-m-d H:i:s') }}</p>

<!-- css -->
<style>
    .time {
        font-size: 3rem;
    }
</style>

<!-- JavaScript -->
<script>
    // 1秒ごとに時間を更新する
    setInterval(function() {
        // 現在の時間を取得
        var currentTime = new Date().toLocaleString('ja-JP', {
            timeZone: 'Asia/Tokyo'
        });

        // テキストを更新
        document.getElementById('current-time').textContent = currentTime;
    }, 1000); // 1000ミリ秒 = 1秒
</script>

<!-- Bladeテンプレート内のHTML -->
<p class="time" id="current-time">{{ now()->format('H:i:s') }}</p>

<style>
    .time {
        font-size: 4rem;
        /* color: darkgrey; */
    }
</style>

<!-- JavaScript -->
<script>
    // 1秒ごとに時間を更新する
    setInterval(function() {
        // 現在の時間を取得
        var currentTime = new Date().toLocaleString('ja-JP', {
            timeZone: 'Asia/Tokyo',
            hour12: false // 24時間表示にする
        });

        // 時間部分のみを取り出す
        var timeParts = currentTime.split(' ')[1]; // 時間部分を取得

        // テキストを更新
        document.getElementById('current-time').textContent = timeParts;
    }, 1000); // 1000ミリ秒 = 1秒
</script>

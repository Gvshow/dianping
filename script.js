// 页面加载时获取点击次数并显示对应文本
window.onload = function() {
    fetch('get_click_count.php')
        .then(response => response.json())
        .then(data => {
            fetch('texts.json')
                .then(response => response.json())
                .then(texts => {
                    // 直接使用点击次数来确定显示哪一条
                    const index = data.clickCount % texts.length;
                    document.getElementById('display-text').textContent = texts[index];
                })
                .catch(error => console.error('无法加载文本:', error));
        })
        .catch(error => console.error('无法获取点击次数:', error));
}

// "换一段"按钮点击事件
document.getElementById('next-btn').addEventListener('click', function() {
    fetch('texts.json')
        .then(response => response.json())
        .then(texts => {
            // 随机选择一个文本
            const randomIndex = Math.floor(Math.random() * texts.length);
            document.getElementById('display-text').textContent = texts[randomIndex];
        })
        .catch(error => console.error('无法加载文本:', error));
});
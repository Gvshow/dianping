if (!sessionStorage.getItem('isAdmin')) {
    alert('请先登录！');
    window.location.href = 'index.html';
}

let texts = [];

// 加载文本数据
async function loadTexts() {
    try {
        const response = await fetch('texts.json');
        texts = await response.json();
        displayTexts();
    } catch (error) {
        console.error('加载文本失败:', error);
        alert('加载文本失败');
    }
}

// 显示文本列表
function displayTexts() {
    const container = document.getElementById('texts-list');
    container.innerHTML = '';
    
    texts.forEach((text, index) => {
        const textDiv = document.createElement('div');
        textDiv.className = 'text-item';
        textDiv.innerHTML = `
            <textarea>${text}</textarea>
            <div class="text-controls">
                <button onclick="deleteText(${index})">删除</button>
            </div>
        `;
        container.appendChild(textDiv);
    });
}

// 添加新文本
document.getElementById('add-text').addEventListener('click', () => {
    texts.push('');
    displayTexts();
});

// 删除文本
function deleteText(index) {
    if (confirm('确定要删除这段文本吗？')) {
        texts.splice(index, 1);
        displayTexts();
    }
}

// 保存更改
document.getElementById('save-changes').addEventListener('click', async () => {
    if (!sessionStorage.getItem('isAdmin')) {
        alert('请先登录！');
        window.location.href = 'index.html';
        return;
    }

    const textareas = document.querySelectorAll('.text-item textarea');
    texts = Array.from(textareas).map(ta => ta.value.trim()).filter(text => text !== '');
    
    try {
        const response = await fetch('save_texts.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(texts)
        });
        
        if (response.ok) {
            alert('保存成功！');
        } else {
            throw new Error('保存失败');
        }
    } catch (error) {
        console.error('保存失败:', error);
        alert('保存失败，请重试');
    }
});

// 页面加载时获取文本数据
loadTexts(); 
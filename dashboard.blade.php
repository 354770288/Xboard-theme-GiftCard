<!doctype html>
<html lang="zh-CN">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no" />
  <title>{{$title}}</title>
  <script type="module" crossorigin src="/theme/{{$theme}}/assets/umi.js"></script>
</head>

<body>

  <script>
    window.routerBase = "/";
    window.settings = {
      title: '{{$title}}',
      assets_path: '/theme/{{$theme}}/assets',
      theme: {
        color: '{{ $theme_config['theme_color'] ?? "default" }}',
      },
      version: '{{$version}}',
      background_url: '{{$theme_config['background_url']}}',
      description: '{{$description}}',
      i18n: [
        'zh-CN',
        'en-US',
        'ja-JP',
        'vi-VN',
        'ko-KR',
        'zh-TW',
        'fa-IR'
      ],
      logo: '{{$logo}}'
    }
  </script>
  <div id="app"></div>
  {!! $theme_config['custom_html'] !!}

  
  <div id="gift-card-modal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background:#fff; padding:20px; border-radius:8px; box-shadow:0 0 10px rgba(0,0,0,0.5); z-index:9999; width: 300px;">
    <h3 style="margin-top:0;">🧧 兑换礼品卡</h3>
    <input type="text" id="gift-card-input" placeholder="请输入兑换码" style="width:100%; padding:8px; margin:10px 0; border:1px solid #ccc; border-radius:4px;">
    <div id="gift-card-msg" style="color:red; font-size:12px; margin-bottom:10px;"></div>
    <div style="text-align:right;">
        <button onclick="closeGiftCard()" style="padding:5px 10px; background:#ccc; border:none; border-radius:4px; cursor:pointer;">取消</button>
        <button onclick="redeemGiftCard()" style="padding:5px 10px; background:#1890ff; color:#fff; border:none; border-radius:4px; cursor:pointer;">立即兑换</button>
    </div>
</div>

<div onclick="openGiftCard()" style="position:fixed; bottom:20px; right:20px; width:50px; height:50px; background:#1890ff; border-radius:50%; display:flex; align-items:center; justify-content:center; cursor:pointer; z-index:9998; box-shadow:0 2px 10px rgba(0,0,0,0.2);">
    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" style="color:white;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
</div>

<script>
    // 打开窗口
    function openGiftCard() {
        document.getElementById('gift-card-modal').style.display = 'block';
        document.getElementById('gift-card-msg').innerText = '';
    }

    // 关闭窗口
    function closeGiftCard() {
        document.getElementById('gift-card-modal').style.display = 'none';
    }

    // 调用后端 API
    function redeemGiftCard() {
        const code = document.getElementById('gift-card-input').value;
        const msgBox = document.getElementById('gift-card-msg');
        
        if (!code) {
            msgBox.innerText = "请输入兑换码";
            return;
        }

        // 发起请求
        fetch('/api/v1/user/gift-card/redeem', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                // 如果是 Xboard 原版，通常会自动在 localStorage 存储 token，需要取出
                'Authorization': localStorage.getItem('token') 
            },
            body: JSON.stringify({ code: code })
        })
        .then(response => response.json())
        .then(data => {
            if (data.data) {
                // 成功逻辑
                alert('✅ ' + data.data.message); 
                closeGiftCard();
                // 刷新页面以更新余额或流量
                window.location.reload();
            } else {
                // 失败逻辑
                msgBox.innerText = '❌ ' + (data.message || '兑换失败');
            }
        })
        .catch(err => {
            console.error(err);
            msgBox.innerText = '❌ 网络请求错误';
        });
    }
</script>
  
</body>

</html>

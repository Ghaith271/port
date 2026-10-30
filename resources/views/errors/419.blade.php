<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#171918">
    <title>انتهت صلاحية الصفحة</title>
    <style>
        :root{color-scheme:dark;--bg:#171918;--lime:#b8c6a0;--text:#f5f5ed;--muted:#a7afa1}
        *{box-sizing:border-box}
        body{min-height:100vh;margin:0;display:grid;place-items:center;padding:24px;background:radial-gradient(ellipse at 50% 40%,#35442a 0,transparent 55%),var(--bg);color:var(--text);font-family:Tajawal,Tahoma,"Segoe UI",Arial,sans-serif;text-align:center}
        main{max-width:560px}
        h1{margin:0 0 12px;font-size:clamp(28px,6vw,42px)}
        p{margin:0 0 28px;color:var(--muted);font-size:17px}
        a{display:inline-flex;min-height:48px;align-items:center;justify-content:center;padding:10px 24px;border-radius:8px;background:var(--lime);color:#20251a;font-weight:700;text-decoration:none}
        a:focus-visible{outline:3px solid var(--text);outline-offset:4px}
    </style>
</head>
<body>
    <main>
        <h1>انتهت صلاحية الصفحة</h1>
        <p>انتهت صلاحية نموذج التواصل. حدّث الصفحة ثم أعد إرسال رسالتك.</p>
        <a href="{{ route('home') }}#contact">العودة إلى نموذج التواصل</a>
    </main>
</body>
</html>

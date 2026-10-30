<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#141714">
    <title>@yield('title', 'لوحة التحكم') — مساحة الإدارة</title>
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.rtl.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
<svg class="icon-library" aria-hidden="true"><defs>
<symbol id="i-grid" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></symbol>
<symbol id="i-user" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21v-2a8 8 0 0 1 16 0v2"/></symbol>
<symbol id="i-folder" viewBox="0 0 24 24"><path d="M3 7V5a2 2 0 0 1 2-2h5l2 3h7a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7Z"/><path d="M3 9h18"/></symbol>
<symbol id="i-code" viewBox="0 0 24 24"><path d="m7 6-6 6 6 6m10-12 6 6-6 6M14 3l-4 18"/></symbol>
<symbol id="i-mail" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="3"/><path d="m3 6 9 7 9-7"/></symbol>
<symbol id="i-arrow" viewBox="0 0 24 24"><path d="M19 12H5m7 7-7-7 7-7"/></symbol>
</defs></svg>
<a class="skip-link" href="#main-content">انتقل إلى المحتوى</a>
<div class="admin-shell">
    <aside class="admin-sidebar">
        <a class="admin-brand" href="{{ route('admin.dashboard') }}"><span class="brand-symbol">غ<span>ـ</span></span><span>مساحة الإدارة<small>PORTFOLIO STUDIO</small></span></a>
        <div class="sidebar-caption">مساحتك الإبداعية</div>
        <nav class="admin-nav" aria-label="القائمة الرئيسية">
            <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><svg><use href="#i-grid"/></svg>نظرة عامة</a>
            <a class="{{ request()->routeIs('admin.profile.*') ? 'active' : '' }}" href="{{ route('admin.profile.edit') }}"><svg><use href="#i-user"/></svg>الملف الشخصي</a>
            <a class="{{ request()->routeIs('admin.projects.*') ? 'active' : '' }}" href="{{ route('admin.projects.index') }}"><svg><use href="#i-folder"/></svg>المشاريع</a>
            <a class="{{ request()->routeIs('admin.skills.*') ? 'active' : '' }}" href="{{ route('admin.skills.index') }}"><svg><use href="#i-code"/></svg>المهارات</a>
            <a class="{{ request()->routeIs('admin.messages.*') ? 'active' : '' }}" href="{{ route('admin.messages.index') }}"><svg><use href="#i-mail"/></svg>الرسائل</a>
        </nav>
        <div class="sidebar-bottom">
            <a class="view-site" href="{{ route('home') }}">استعرض البورتفوليو <svg><use href="#i-arrow"/></svg></a>
            <div class="admin-account"><span class="account-avatar">{{ mb_substr(auth()->user()->name ?? 'مدير', 0, 1) }}</span><div><strong>{{ auth()->user()->name }}</strong><small>مدير الموقع</small></div><form method="POST" action="{{ route('logout') }}">@csrf<button class="logout-button" aria-label="تسجيل الخروج" title="تسجيل الخروج"><svg class="ui-icon icon-logout" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M9 4H4v16h5m-1-8h13m-5-5 5 5-5 5"/></svg></button></form></div>
        </div>
    </aside>
    <div class="admin-workspace">
        <header class="admin-topbar"><span><span class="status-dot"></span>لوحة التحكم</span><div class="topbar-actions"><a href="{{ route('home') }}">العودة إلى الموقع <span aria-hidden="true"><svg class="ui-icon icon-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 19 19 5M5 5h14v14"/></svg></span></a><form class="mobile-logout" method="POST" action="{{ route('logout') }}">@csrf<button type="submit">تسجيل الخروج</button></form></div></header>
        <main id="main-content" class="admin-main">
            <div class="page-header"><div><p class="eyebrow">@yield('eyebrow', 'كل شيء، في مكان واحد')</p><h1>@yield('heading', 'لوحة التحكم')</h1><p class="page-description">@yield('description')</p></div><div class="page-actions">@yield('actions')</div></div>
            @if(session('success'))<div class="notice notice-success" role="status"><span aria-hidden="true"><svg class="ui-icon icon-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m5 12 4 4L19 6"/></svg></span> {{ session('success') }}</div>@endif
            @if(session('error'))<div class="notice notice-error" role="alert">{{ session('error') }}</div>@endif
            @if($errors->any())<div class="notice notice-error" role="alert"><strong>يرجى مراجعة البيانات التالية:</strong><ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            @yield('content')
            <footer class="admin-footer"><span>مساحة صغيرة لأفكار كبيرة.</span><span>صُنع بشغف <span class="lime-dot"><svg class="ui-icon icon-star" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M12 2v20M2 12h20M5 5l14 14M5 19 19 5"/></svg></span></span></footer>
        </main>
    </div>
</div>
<script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}" defer></script>
@stack('scripts')
</body>
</html>

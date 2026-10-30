<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#171918">
    <meta name="description" content="{{ $profile->tagline }}">
    <title>{{ $profile->name }} — {{ $profile->title }}</title>
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.rtl.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/portfolio.css') }}">
    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}" defer></script>
    <script src="{{ asset('js/portfolio.js') }}" defer></script>
</head>
<body>
    <a class="skip-link" href="#main">انتقل إلى المحتوى</a>
    <div class="site-noise" aria-hidden="true"></div>
    <header class="site-header" id="top">
        <div class="page-shell nav-shell">
            <a href="{{ route('home') }}" class="wordmark" aria-label="{{ $profile->name }} — الرئيسية">{{ $profile->name }}<span>.</span></a>
            <button class="nav-toggle" type="button" aria-controls="primary-nav" aria-expanded="false" aria-label="فتح القائمة">
                <span></span><span></span>
            </button>
            <nav class="primary-nav" id="primary-nav" aria-label="القائمة الرئيسية">
                <a href="#home" class="nav-item active">الرئيسية</a>
                <a href="#work" class="nav-item">أعمالي</a>
                <a href="#about" class="nav-item">عني</a>
                <a href="#skills" class="nav-item">مهاراتي</a>
            </nav>
            <a href="#contact" class="nav-contact">لنتحدث <span aria-hidden="true"><svg class="icon-arrow" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19 19 5M5 5h14v14"/></svg></span></a>
        </div>
    </header>

    <main id="main">
        <section class="hero page-shell" id="home" aria-labelledby="hero-title">
            <div class="hero-copy">
                <div class="eyebrow reveal"><span class="status-dot"></span>{{ $profile->available ? 'متاح لمشاريع جديدة' : 'أهلًا بك في مساحتي الرقمية' }}</div>
                <h1 id="hero-title" class="reveal">أحوّل الأفكار<br>إلى <span class="accent-underline">تجارب رقمية<span class="hand-spark" aria-hidden="true"><svg class="icon-star" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2v20M2 12h20M5 5l14 14M19 5 5 19"/></svg></span></span><br>تترك أثرًا<span class="lime-text">.</span></h1>
                <p class="hero-description reveal">أنا {{ $profile->name }}، {{ $profile->title }}.<br>{{ $profile->tagline }}</p>
                <div class="hero-actions reveal">
                    <a href="#work" class="button-lime">اكتشف أعمالي <span aria-hidden="true"><svg class="icon-arrow" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 5 5 19M5 5v14h14"/></svg></span></a>
                    <a href="#contact" class="text-link">لنبنِ شيئًا مميزًا <span aria-hidden="true"><svg class="icon-arrow" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19 19 5M5 5h14v14"/></svg></span></a>
                </div>
                <div class="hero-footnote reveal"><span class="tiny-cross" aria-hidden="true">+</span> شغف بالتفاصيل. اهتمام بالتجربة.</div>
            </div>

            <div class="hero-art reveal" aria-hidden="true">
                <div class="art-grid"></div>
                <div class="art-label">CREATIVE DEVELOPER</div>
                <div class="orbit orbit-one"></div><div class="orbit orbit-two"></div><div class="orbit orbit-three"></div>
                <div class="orbit-dot dot-one"></div><div class="orbit-dot dot-two"></div>
                <div class="art-center"><span>&lt;</span><i></i><span>/&gt;</span></div>
                <div class="floating-tag tag-design"><span>✧</span> تصميم بإحساس</div>
                <div class="floating-tag tag-code"><span>⌘</span> كود بشغف</div>
                <div class="art-bottom"><span>IDEAS → EXPERIENCES</span><span>01 / ∞</span></div>
                <div class="art-corner corner-top"></div><div class="art-corner corner-bottom"></div>
            </div>
            <a href="#work" class="scroll-cue"><span class="scroll-circle" aria-hidden="true">↓</span><span>هناك المزيد بالأسفل</span></a>
        </section>

        <div class="marquee" aria-hidden="true"><div class="marquee-track"><span>تصميم واعٍ</span><i><svg class="icon-star" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2v20M2 12h20M5 5l14 14M19 5 5 19"/></svg></i><span>تجربة سلسة</span><i><svg class="icon-star" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2v20M2 12h20M5 5l14 14M19 5 5 19"/></svg></i><span>كود نظيف</span><i><svg class="icon-star" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2v20M2 12h20M5 5l14 14M19 5 5 19"/></svg></i><span>تفاصيل تصنع الفرق</span><i><svg class="icon-star" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2v20M2 12h20M5 5l14 14M19 5 5 19"/></svg></i><span>تصميم واعٍ</span><i><svg class="icon-star" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2v20M2 12h20M5 5l14 14M19 5 5 19"/></svg></i><span>تجربة سلسة</span><i><svg class="icon-star" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2v20M2 12h20M5 5l14 14M19 5 5 19"/></svg></i><span>كود نظيف</span><i><svg class="icon-star" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2v20M2 12h20M5 5l14 14M19 5 5 19"/></svg></i><span>تفاصيل تصنع الفرق</span><i><svg class="icon-star" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2v20M2 12h20M5 5l14 14M19 5 5 19"/></svg></i></div></div>

        <section class="section-block page-shell" id="work" aria-labelledby="work-title">
            <div class="section-heading reveal">
                <div><p class="section-kicker"><span>01</span> مختارات من أعمالي</p><h2 id="work-title">أفكار أخذت <span class="muted-word">شكلها.</span></h2></div>
                <p class="section-description">كل مشروع حكاية جديدة،<br>وكل تفصيل له سبب.</p>
            </div>
            @if($projects->isNotEmpty())
                <div class="work-filters reveal" role="group" aria-label="تصفية المشاريع حسب المجال">
                    <button type="button" class="filter-button active" data-filter="all" aria-pressed="true">كل الأعمال <span>{{ $projects->count() }}</span></button>
                    @foreach($projects->pluck('category')->filter()->unique() as $category)
                        <button type="button" class="filter-button" data-filter="{{ $category }}" aria-pressed="false">{{ $category }}</button>
                    @endforeach
                </div>
                <p class="visually-hidden" id="filter-status" aria-live="polite"></p>
                <div class="projects-grid">
                    @foreach($projects as $project)
                        <article class="project-card reveal" data-category="{{ $project->category }}" style="--project-accent: {{ $project->accent_color ?: '#b8c6a0' }}">
                            <div class="project-art project-art-{{ $loop->index % 4 }}" style="--project-accent: {{ $project->accent_color ?? '#b8c6a0' }}">
                                @if($project->image_path)
                                    <img class="project-image" src="{{ asset('storage/'.$project->image_path) }}" alt="واجهة مشروع {{ $project->title }}" loading="lazy">
                                @else
                                    <div class="project-mockup" aria-hidden="true">
                                        <div class="mockup-top"><span class="mockup-logo">{{ mb_substr($project->title, 0, 1) }}<i>.</i></span><div class="mockup-nav"><i></i><i></i><i></i></div><span class="mockup-menu"><svg class="icon-arrow" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19 19 5M5 5h14v14"/></svg></span></div>
                                        <div class="mockup-content"><span class="mockup-eyebrow">{{ $project->category }}</span><strong>{{ $project->title }}<em>صُمّم ليُلهم.</em></strong><span class="mockup-button">اكتشف التجربة <b><svg class="icon-arrow" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19 19 5M5 5h14v14"/></svg></b></span></div>
                                        <div class="mockup-shape"><div></div><div></div><div></div></div>
                                        <div class="mockup-footer"><span>DESIGNED WITH PURPOSE</span><span><svg class="icon-star" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2v20M2 12h20M5 5l14 14M19 5 5 19"/></svg></span></div>
                                    </div>
                                    <span class="project-corner-star" aria-hidden="true"><svg class="icon-star" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2v20M2 12h20M5 5l14 14M19 5 5 19"/></svg></span>
                                @endif
                                <span class="project-number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="project-info">
                                <div class="project-title-row"><h3>{{ $project->title }}</h3><span class="project-category">{{ $project->category }}</span></div>
                                <p>{{ $project->description }}</p>
                                <div class="project-tags">@foreach(($project->tech_stack ?? []) as $technology)<span>{{ $technology }}</span>@endforeach</div>
                                @if($project->project_url || $project->github_url)
                                    <div class="project-links">
                                        @if($project->project_url)<a href="{{ $project->project_url }}" target="_blank" rel="noopener noreferrer">زيارة المشروع <span aria-hidden="true"><svg class="icon-arrow" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19 19 5M5 5h14v14"/></svg></span><span class="visually-hidden"> — يفتح في نافذة جديدة</span></a>@endif
                                        @if($project->github_url)<a href="{{ $project->github_url }}" target="_blank" rel="noopener noreferrer">الكود المصدري <span aria-hidden="true"><svg class="icon-arrow" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19 19 5M5 5h14v14"/></svg></span><span class="visually-hidden"> — يفتح في نافذة جديدة</span></a>@endif
                                    </div>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="empty-state">قصص جديدة قيد البناء. ترقّب المشاريع القادمة.</div>
            @endif
        </section>

        <section class="about-section" id="about" aria-labelledby="about-title">
            <div class="page-shell about-grid">
                <div class="about-visual reveal" aria-hidden="true"><div class="about-symbol"><svg class="icon-star" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2v20M2 12h20M5 5l14 14M19 5 5 19"/></svg></div><span class="about-label">فضول دائم.<br>إبداع بلا حدود.</span><span class="about-coordinate">BUILD · LEARN · REPEAT</span></div>
                <div class="about-copy reveal"><p class="section-kicker"><span>02</span> خلف الشاشة</p><h2 id="about-title">أكثر من كود.<br><span class="muted-word">طريقة تفكير.</span></h2><p class="about-bio">{!! nl2br(e($profile->bio)) !!}</p>
                    <div class="about-details">
                        @if($profile->location)<div><span class="detail-label">أعمل من</span><strong>{{ $profile->location }}</strong></div>@endif
                        <div><span class="detail-label">اهتمامي الأول</span><strong>تجربة المستخدم</strong></div>
                        <div><span class="detail-label">فلسفتي</span><strong>البساطة تصنع الفرق</strong></div>
                    </div>
                    <a href="#contact" class="text-link">يسعدني التعرف عليك <span aria-hidden="true"><svg class="icon-arrow" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19 19 5M5 5h14v14"/></svg></span></a>
                </div>
            </div>
        </section>

        <section class="section-block page-shell" id="skills" aria-labelledby="skills-title">
            <div class="section-heading reveal"><div><p class="section-kicker"><span>03</span> أدواتي المفضلة</p><h2 id="skills-title">الفكرة تحتاج <span class="muted-word">الأداة المناسبة.</span></h2></div><p class="section-description">أتعلّم باستمرار، وأختار ما<br>يخدم الفكرة بأفضل صورة.</p></div>
            <div class="skills-grid">
                @forelse($skills as $skill)
                    <div class="skill-card reveal"><div class="skill-top"><span class="skill-icon" aria-hidden="true">{{ ['{ }', '</>', 'JS', 'DB', 'UI', '<>'][$loop->index % 6] }}</span><span class="skill-category">{{ $skill->category }}</span></div><h3 dir="auto">{{ $skill->name }}</h3><div class="skill-bottom"><span>مستوى الإتقان</span><span>{{ $skill->level }}%</span></div><div class="skill-meter" role="meter" aria-label="إتقان {{ $skill->name }}" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $skill->level }}"><span style="--skill-level: {{ max(0, min(100, $skill->level)) }}%"></span></div></div>
                @empty
                    <p class="empty-state">قائمة المهارات قيد التحديث.</p>
                @endforelse
            </div>
        </section>

        <section class="contact-section page-shell" id="contact" aria-labelledby="contact-title">
            <div class="contact-panel">
                <div class="contact-intro reveal"><p class="section-kicker"><span>04</span> الخطوة الأولى تبدأ برسالة</p><h2 id="contact-title">لديك فكرة؟<br>لنجعلها <span>حقيقية<svg viewBox="0 0 250 18" aria-hidden="true"><path d="M3 12 Q112 0 247 9 M12 17 Q113 5 237 14"/></svg></span><span class="lime-text">.</span></h2><p>مشروع جديد، فرصة تعاون، أو حتى مجرد مرحبًا.<br>يسعدني سماعك.</p>
                    @if($profile->email)<a class="contact-email" href="mailto:{{ $profile->email }}" dir="ltr">{{ $profile->email }} <span aria-hidden="true"><svg class="icon-arrow" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19 19 5M5 5h14v14"/></svg></span></a>@endif
                    @if($profile->phone)
                        <div class="contact-phone-links">
                            <a href="tel:{{ $profile->phone }}" aria-label="اتصال هاتفي على {{ $profile->phone }}"><span class="channel-label">اتصال مباشر</span><span dir="ltr">{{ $profile->phone }}</span></a>
                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $profile->phone) }}" target="_blank" rel="noopener noreferrer" aria-label="تواصل عبر واتساب — يفتح في نافذة جديدة">واتساب <span aria-hidden="true"><svg class="icon-arrow" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19 19 5M5 5h14v14"/></svg></span></a>
                        </div>
                    @endif
                    <div class="social-links">
                        @if($profile->instagram_url)<a href="{{ $profile->instagram_url }}" target="_blank" rel="noopener noreferrer">Instagram <span aria-hidden="true"><svg class="icon-arrow" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19 19 5M5 5h14v14"/></svg></span><span class="visually-hidden"> — يفتح في نافذة جديدة</span></a>@endif
                        @if($profile->github_url)<a href="{{ $profile->github_url }}" target="_blank" rel="noopener noreferrer">GitHub <span aria-hidden="true"><svg class="icon-arrow" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19 19 5M5 5h14v14"/></svg></span><span class="visually-hidden"> — يفتح في نافذة جديدة</span></a>@endif
                        @if($profile->linkedin_url)<a href="{{ $profile->linkedin_url }}" target="_blank" rel="noopener noreferrer">LinkedIn <span aria-hidden="true"><svg class="icon-arrow" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19 19 5M5 5h14v14"/></svg></span><span class="visually-hidden"> — يفتح في نافذة جديدة</span></a>@endif
                    </div>
                </div>
                <form class="contact-form reveal" action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    @if(session('success'))<div class="form-feedback feedback-success" role="status">{{ session('success') }}</div>@endif
                    @if($errors->any())<div class="form-feedback feedback-error" role="alert">تعذّر إرسال الرسالة. راجع الحقول المبيّنة وحاول مجددًا.@error('website')<br>{{ $message }}@enderror</div>@endif
                    <div class="row g-4">
                        <div class="col-sm-6"><label for="contact-name">ما اسمك؟ <span>*</span></label><input id="contact-name" type="text" name="name" value="{{ old('name') }}" placeholder="الاسم الكريم" autocomplete="name" maxlength="100" required @error('name') aria-invalid="true" aria-describedby="error-name" @enderror>@error('name')<span class="field-error" id="error-name">{{ $message }}</span>@enderror</div>
                        <div class="col-sm-6"><label for="contact-email">بريدك الإلكتروني <span>*</span></label><input id="contact-email" type="email" name="email" value="{{ old('email') }}" placeholder="hello@example.com" autocomplete="email" maxlength="254" required dir="ltr" @error('email') aria-invalid="true" aria-describedby="error-email" @enderror>@error('email')<span class="field-error" id="error-email">{{ $message }}</span>@enderror</div>
                        <div class="col-12"><label for="contact-subject">عن ماذا نتحدث؟ <span>*</span></label><input id="contact-subject" type="text" name="subject" value="{{ old('subject') }}" placeholder="مشروع جديد، تعاون، أو فكرة جميلة..." maxlength="200" required @error('subject') aria-invalid="true" aria-describedby="error-subject" @enderror>@error('subject')<span class="field-error" id="error-subject">{{ $message }}</span>@enderror</div>
                        <div class="col-12"><label for="contact-message">أخبرني المزيد <span>*</span></label><textarea id="contact-message" name="message" rows="3" placeholder="مساحتك للحديث عن فكرتك..." minlength="10" maxlength="5000" required @error('message') aria-invalid="true" aria-describedby="error-message" @enderror>{{ old('message') }}</textarea>@error('message')<span class="field-error" id="error-message">{{ $message }}</span>@enderror</div>
                    </div>
                    <div class="form-trap" aria-hidden="true"><label for="contact-website">اترك هذا الحقل فارغًا</label><input id="contact-website" type="text" name="website" tabindex="-1" autocomplete="off"></div>
                    <button type="submit" class="button-lime contact-submit">أرسل رسالتك <span aria-hidden="true"><svg class="icon-arrow" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19 19 5M5 5h14v14"/></svg></span></button>
                    <p class="form-note">تُحفظ رسالتك بشكل خاص للرد عليك.</p>
                </form>
            </div>
        </section>
    </main>

    <footer class="site-footer page-shell"><div><a href="#top" class="wordmark">{{ $profile->name }}<span>.</span></a><p>صُنع بشغف، مع اهتمام بكل تفصيل.</p></div><div class="footer-meta"><span>© {{ date('Y') }} {{ $profile->name }}</span><a href="{{ route('login') }}">لوحة التحكم <span aria-hidden="true"><svg class="icon-arrow" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19 19 5M5 5h14v14"/></svg></span></a></div><a href="#top" class="back-to-top" aria-label="العودة إلى أعلى الصفحة">↑</a></footer>
</body>
</html>

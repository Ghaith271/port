import { test, expect } from '@playwright/test';

const loginAsTestAdmin = async page => {
    if (!process.env.BROWSER_TEST_EMAIL || !process.env.BROWSER_TEST_PASSWORD) {
        throw new Error('Run npm run test:browser to provision an isolated administrator.');
    }
    await page.goto('/login');
    await page.getByLabel('البريد الإلكتروني', { exact: true }).fill(process.env.BROWSER_TEST_EMAIL);
    await page.getByLabel('كلمة المرور', { exact: true }).fill(process.env.BROWSER_TEST_PASSWORD);
    await page.getByRole('button', { name: /الدخول إلى مساحتك/ }).click();
    await expect(page).toHaveURL(/\/admin$/);
};

const noHorizontalOverflow = async page => {
    const dimensions = await page.evaluate(() => ({
        viewport: document.documentElement.clientWidth,
        content: document.documentElement.scrollWidth,
    }));
    expect(dimensions.content, 'The page must fit the viewport without horizontal scrolling')
        .toBeLessThanOrEqual(dimensions.viewport + 1);
};

test('public portfolio renders its content and local assets on desktop', async ({ page }) => {
    const errors = [];
    const assetFailures = [];
    page.on('pageerror', error => errors.push(error.message));
    page.on('response', response => {
        if (/\.(css|js|woff2)(\?|$)/.test(response.url()) && response.status() >= 400) {
            assetFailures.push(`${response.status()} ${response.url()}`);
        }
    });
    const response = await page.goto('/');
    expect(response.status()).toBe(200);
    await expect(page.getByRole('heading', { level: 1 })).toBeVisible();
    await expect(page.locator('.project-card').first()).toBeAttached();
    await expect(page.locator('html')).toHaveAttribute('dir', 'rtl');
    await page.evaluate(() => document.fonts.ready);
    await noHorizontalOverflow(page);
    await page.waitForTimeout(900);
    await page.screenshot({ path: '/tmp/portfolio-desktop.png', animations: 'disabled' });
    expect(errors).toEqual([]);
    expect(assetFailures).toEqual([]);
});

test('project category filters show only matching work and reset correctly', async ({ page }) => {
    await page.goto('/');
    const projects = page.locator('.project-card');
    const total = await projects.count();
    expect(total).toBeGreaterThan(0);
    const categoryButton = page.locator('[data-filter]:not([data-filter="all"])').first();
    await expect(categoryButton).toBeVisible();
    const category = await categoryButton.getAttribute('data-filter');
    await categoryButton.click();
    await expect(categoryButton).toHaveAttribute('aria-pressed', 'true');
    const visibleCategories = await projects.evaluateAll(cards => cards.filter(card => !card.hidden).map(card => card.dataset.category));
    expect(visibleCategories.length).toBeGreaterThan(0);
    expect(visibleCategories.every(value => value === category)).toBe(true);
    await page.getByRole('button', { name: /^كل الأعمال/ }).click();
    await expect(page.locator('.project-card:not([hidden])')).toHaveCount(total);
});

test('mobile navigation opens, navigates, and closes without horizontal overflow', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await page.goto('/');
    await page.evaluate(() => document.fonts.ready);
    const toggle = page.locator('.nav-toggle');
    await expect(toggle).toBeVisible();
    await expect(toggle).toHaveAccessibleName('فتح القائمة');
    await expect(page.getByRole('navigation', { name: 'القائمة الرئيسية' })).toBeHidden();
    await noHorizontalOverflow(page);
    await page.waitForTimeout(900);
    await page.screenshot({ path: '/tmp/portfolio-mobile.png', animations: 'disabled' });
    await toggle.click();
    await expect(toggle).toHaveAttribute('aria-expanded', 'true');
    await page.getByRole('navigation').getByRole('link', { name: 'أعمالي', exact: true }).click();
    await expect(toggle).toHaveAttribute('aria-expanded', 'false');
    await expect(page).toHaveURL(/#work$/);
    for (const section of ['work', 'about', 'skills', 'contact']) {
        await page.locator(`#${section}`).scrollIntoViewIfNeeded();
        await noHorizontalOverflow(page);
    }
    await toggle.scrollIntoViewIfNeeded();
    await toggle.click();
    await page.keyboard.press('Escape');
    await expect(toggle).toHaveAttribute('aria-expanded', 'false');
    await expect(toggle).toBeFocused();
});

test('reduced-motion preference leaves content visible and stops decorative motion', async ({ page }) => {
    await page.emulateMedia({ reducedMotion: 'reduce' });
    await page.goto('/');
    await expect(page.getByRole('heading', { level: 1 })).toBeVisible();
    const motion = await page.locator('.orbit-one').evaluate(element => getComputedStyle(element).animationName);
    expect(motion).toBe('none');
    const scrolling = await page.locator('html').evaluate(element => getComputedStyle(element).scrollBehavior);
    expect(scrolling).toBe('auto');
});

test('contact form submits successfully through the real application', async ({ page }) => {
    await page.goto('/');
    await expect(page.locator('.contact-form')).toHaveAttribute('action', '/contact');
    await page.locator('#contact-name').fill('اختبار متصفح');
    await page.locator('#contact-email').fill('smoke-browser@example.test');
    await page.locator('#contact-subject').fill('Browser smoke test');
    await page.locator('#contact-message').fill('هذه رسالة اختبار آلية للتحقق من نموذج التواصل وحفظ البيانات.');
    await page.getByRole('button', { name: 'أرسل رسالتك' }).click();
    await expect(page.locator('.feedback-success')).toContainText('وصلت رسالتك بنجاح');
    await expect(page).toHaveURL(/#contact$/);
    await expect(page.locator('#contact-name')).toHaveValue('');
    await loginAsTestAdmin(page);
    await page.goto('/admin/messages');
    await expect(page.getByText('smoke-browser@example.test', { exact: true })).toBeVisible();
});

test('contact validation errors preserve the submitted fields in the browser', async ({ page }) => {
    await page.goto('/');
    await page.locator('#contact-name').fill('زائر الاختبار');
    await page.locator('#contact-email').fill('invalid-email');
    await page.locator('#contact-subject').fill('تحقق من الحقول');
    await page.locator('#contact-message').fill('رسالة اختبار غير صالحة يجب ألا تحفظ.');
    await page.locator('.contact-form').evaluate(form => { form.noValidate = true; });
    await page.getByRole('button', { name: 'أرسل رسالتك' }).click();

    await expect(page.locator('.feedback-error')).toContainText('تعذّر إرسال الرسالة');
    await expect(page.locator('#contact-name')).toHaveValue('زائر الاختبار');
    await expect(page.locator('#contact-email')).toHaveValue('invalid-email');
    await expect(page.locator('#contact-subject')).toHaveValue('تحقق من الحقول');
    await expect(page.locator('#contact-message')).toHaveValue('رسالة اختبار غير صالحة يجب ألا تحفظ.');
});

test('an expired contact session shows refresh guidance without bypassing CSRF', async ({ page, context }) => {
    await page.goto('/');
    await context.clearCookies();
    await page.locator('#contact-name').fill('جلسة منتهية');
    await page.locator('#contact-email').fill('expired-session@example.test');
    await page.locator('#contact-subject').fill('اختبار انتهاء الجلسة');
    await page.locator('#contact-message').fill('لا ينبغي حفظ الإرسال بعد انتهاء الجلسة.');
    await page.getByRole('button', { name: 'أرسل رسالتك' }).click();

    await expect(page.getByRole('heading', { name: 'انتهت صلاحية الصفحة' })).toBeVisible();
    await expect(page.getByText('حدّث الصفحة ثم أعد إرسال رسالتك.')).toBeVisible();
});

test('contact submissions without a CSRF token are rejected', async ({ request }) => {
    const response = await request.post('/contact', {
        form: {
            name: 'CSRF browser check',
            email: 'csrf-browser@example.test',
            subject: 'This request must fail',
            message: 'Missing CSRF tokens must never be accepted.',
        },
    });
    expect(response.status()).toBe(419);
    expect(await response.text()).toContain('انتهت صلاحية نموذج التواصل');
});

test('anonymous admin visitors are redirected to an accessible login form', async ({ page }) => {
    const errors = [];
    page.on('pageerror', error => errors.push(error.message));
    await page.goto('/admin');
    await expect(page).toHaveURL(/\/login$/);
    await expect(page.getByLabel('البريد الإلكتروني', { exact: true })).toBeVisible();
    await expect(page.getByLabel('كلمة المرور', { exact: true })).toBeVisible();
    await page.getByRole('button', { name: 'إظهار', exact: true }).click();
    await expect(page.getByLabel('كلمة المرور', { exact: true })).toHaveAttribute('type', 'text');
    await page.getByRole('button', { name: 'إخفاء', exact: true }).click();
    await expect(page.getByLabel('كلمة المرور', { exact: true })).toHaveAttribute('type', 'password');
    await noHorizontalOverflow(page);
    expect(errors).toEqual([]);
});

test('administrator can create, update, publish, and delete a project', async ({ page }) => {
    const errors = [];
    page.on('pageerror', error => errors.push(error.message));
    await loginAsTestAdmin(page);
    await page.evaluate(() => document.fonts.ready);
    await page.screenshot({ path: '/tmp/portfolio-admin.png', animations: 'disabled' });
    await noHorizontalOverflow(page);
    await page.setViewportSize({ width: 390, height: 844 });
    await noHorizontalOverflow(page);
    await page.screenshot({ path: '/tmp/portfolio-admin-mobile.png', animations: 'disabled' });
    await page.setViewportSize({ width: 1440, height: 1000 });

    await page.goto('/admin/projects/create');
    await page.locator('#title').fill('Browser workflow project');
    await page.locator('#slug').fill('browser-workflow-project');
    await page.locator('#category').fill('اختبارات متصفح');
    await page.locator('#description').fill('مشروع اختبار للتحقق من دورة الإضافة والتعديل والنشر والحذف عبر لوحة التحكم.');
    await page.locator('#tech_stack').fill('Laravel, JavaScript, Bootstrap');
    await page.locator('#image').setInputFiles({
        name: 'browser-project.png',
        mimeType: 'image/png',
        buffer: Buffer.from('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+j+XcAAAAASUVORK5CYII=', 'base64'),
    });
    await page.getByLabel('نشر في البورتفوليو', { exact: true }).check();
    await page.getByRole('button', { name: /إضافة المشروع/ }).click();
    await expect(page.getByRole('status')).toContainText('تمت إضافة المشروع');
    let row = page.getByRole('row').filter({ hasText: 'Browser workflow project' });
    await expect(row).toBeVisible();
    await row.getByRole('link', { name: 'تعديل', exact: true }).click();
    await page.locator('#title').fill('Browser workflow project updated');
    await page.getByRole('button', { name: /حفظ التغييرات/ }).click();
    await expect(page.getByRole('status')).toContainText('تم تحديث المشروع');
    await page.goto('/');
    await expect(page.getByRole('heading', { name: 'Browser workflow project updated', exact: true })).toBeVisible();
    const projectImage = page.locator('img[alt="صورة مشروع Browser workflow project updated"]');
    await expect(projectImage).toBeVisible();
    await expect(projectImage).toHaveAttribute('loading', 'lazy');
    await expect(projectImage).toHaveCSS('object-fit', 'cover');
    expect(await projectImage.evaluate(image => image.naturalWidth)).toBeGreaterThan(0);
    await noHorizontalOverflow(page);
    await page.setViewportSize({ width: 390, height: 844 });
    await expect(projectImage).toBeVisible();
    await noHorizontalOverflow(page);
    const mobileImage = await projectImage.boundingBox();
    expect(mobileImage.width).toBeLessThanOrEqual(390);
    await page.setViewportSize({ width: 1440, height: 1000 });

    await page.goto('/admin/projects');
    row = page.getByRole('row').filter({ hasText: 'Browser workflow project updated' });
    page.once('dialog', dialog => dialog.accept());
    await row.getByRole('button', { name: 'حذف مشروع Browser workflow project updated', exact: true }).click();
    await expect(page.getByRole('status')).toContainText('تم حذف المشروع');
    await expect(page.getByRole('row').filter({ hasText: 'Browser workflow project updated' })).toHaveCount(0);
    await page.goto('/');
    await expect(page.getByRole('heading', { name: 'Browser workflow project updated', exact: true })).toHaveCount(0);
    expect(errors).toEqual([]);
});

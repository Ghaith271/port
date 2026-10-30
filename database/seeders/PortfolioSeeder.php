<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        // Idempotent: running the seeder again never overwrites edited content.
        $profile = Profile::firstOrCreate(['id' => 1], [
            'name' => 'غيث', 'title' => 'مطوّر ويب',
            'tagline' => 'أحوّل الأفكار إلى تجارب رقمية.',
            'bio' => 'أهلاً، أنا غيث. أحب بناء مواقع تجمع بين بساطة الاستخدام وجمال التفاصيل. أعمل على تطوير واجهات تفاعلية وأنظمة خلفية متينة، من الفكرة الأولى حتى آخر سطر كود. هذا محتوى تجريبي يمكنك تخصيصه من لوحة التحكم.',
            'email' => null, 'location' => 'متاح للعمل عن بُعد',
            'available' => true,
            'instagram_url' => 'https://www.instagram.com/ghaith.m717/',
            'phone' => '+905354256484',
        ]);

        if (! $profile->wasRecentlyCreated) {
            return;
        }

        $projects = [
            ['title' => 'مدار — منصة إدارة المشاريع', 'slug' => 'madar', 'category' => 'تطبيقات ويب', 'description' => 'تصوّر تجريبي لمساحة عمل تجمع المهام والفريق في تجربة واضحة ومرنة.', 'tech_stack' => ['Laravel', 'JavaScript', 'SQL'], 'accent_color' => '#b8c6a0', 'featured' => true],
            ['title' => 'أُفق — متجر بتجربة مختلفة', 'slug' => 'ofuq', 'category' => 'واجهات تفاعلية', 'description' => 'نموذج واجهة لمتجر عصري يوازن بين عرض المنتجات وسهولة الوصول.', 'tech_stack' => ['Bootstrap', 'JavaScript', 'CSS'], 'accent_color' => '#c2b7cd', 'featured' => true],
            ['title' => 'نبض — لوحة تحليلات', 'slug' => 'nabd', 'category' => 'لوحات تحكم', 'description' => 'تصوّر للوحة بيانات تساعد على قراءة الأرقام وفهم الأداء بنظرة واحدة.', 'tech_stack' => ['PHP', 'Laravel', 'SQL'], 'accent_color' => '#c8ad98', 'featured' => false],
            ['title' => 'مسار — تجربة حجز', 'slug' => 'masar', 'category' => 'تطبيقات ويب', 'description' => 'نموذج لتجربة حجز متجاوبة، بخطوات بسيطة واهتمام بالتفاصيل الصغيرة.', 'tech_stack' => ['JavaScript', 'Bootstrap', 'Laravel'], 'accent_color' => '#a5bec0', 'featured' => false],
        ];
        foreach ($projects as $i => $project) {
            Project::firstOrCreate(['slug' => $project['slug']], $project + ['sort_order' => $i, 'published' => true]);
        }
        $skills = [
            ['JavaScript', 'الواجهة الأمامية', 90], ['Bootstrap', 'الواجهة الأمامية', 92],
            ['HTML & CSS', 'الواجهة الأمامية', 95], ['PHP', 'التطوير الخلفي', 87],
            ['Laravel', 'التطوير الخلفي', 90], ['SQL', 'قواعد البيانات', 85],
            ['Git', 'أدوات العمل', 88], ['REST APIs', 'التطوير الخلفي', 86],
        ];
        foreach ($skills as $i => [$name, $category, $level]) {
            Skill::firstOrCreate(['name' => $name], compact('category', 'level') + ['sort_order' => $i]);
        }
    }
}

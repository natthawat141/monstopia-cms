<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Company;
use App\Models\Project;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@monstopia.co.th'],
            ['name' => 'MONSTOPIA Admin', 'password' => 'password', 'role' => 'admin', 'email_verified_at' => now()],
        );
        User::updateOrCreate(
            ['email' => 'editor@monstopia.co.th'],
            ['name' => 'MONSTOPIA Editor', 'password' => 'password', 'role' => 'editor', 'email_verified_at' => now()],
        );

        Company::updateOrCreate(
            ['slug' => 'monstopia'],
            [
                'name' => 'MONSTOPIA',
                'legal_name' => 'Monstopia Company Limited',
                'registered_at' => '2023-10-16',
                'province' => 'ปทุมธานี',
                'business_type' => 'กิจกรรมบริการเทคโนโลยีสารสนเทศและคอมพิวเตอร์อื่น ๆ',
                'description' => 'ผู้พัฒนาเกม NFT และผู้พัฒนาโครงการดิจิทัลที่เชื่อมโยงเทคโนโลยี บล็อกเชน และประสบการณ์การเรียนรู้',
                'published' => true,
            ],
        );

        $categories = collect([
            ['name' => 'Web Application', 'slug' => 'web-application'],
            ['name' => 'Mobile Application', 'slug' => 'mobile-application'],
            ['name' => 'AI & Data', 'slug' => 'ai-data'],
            ['name' => 'Blockchain & NFT', 'slug' => 'blockchain-nft'],
            ['name' => 'Digital Transformation', 'slug' => 'digital-transformation'],
        ])->mapWithKeys(fn ($category) => [$category['slug'] => Category::updateOrCreate(['slug' => $category['slug']], [...$category, 'status' => 'active'])]);

        Project::updateOrCreate(
            ['slug' => 'bullmoonjr-nft'],
            [
                'category_id' => $categories['blockchain-nft']->id,
                'title' => 'BullMoonJR NFT',
                'description' => 'โครงการ NFT ที่ Monstopia ร่วมพัฒนากับ Bitkub Blockchain Technology และ Stock2morrow เพื่อส่งเสริมความรู้ด้านธุรกิจและการลงทุนให้กับนักเรียน นักศึกษา และผู้สนใจทั่วไป',
                'client_name' => 'Stock2morrow / Bitkub Blockchain Technology',
                'project_url' => null,
                'image' => null,
                'status' => 'published',
                'published_at' => '2025-05-27 09:00:00',
            ],
        );
        Project::factory()->count(9)->create(['category_id' => $categories['web-application']->id, 'status' => 'published']);

        Article::factory()->count(10)->create(['category_id' => $categories['digital-transformation']->id]);

        foreach ([
            ['name' => 'Web Development', 'icon' => 'code-2'],
            ['name' => 'Mobile Application', 'icon' => 'smartphone'],
            ['name' => 'AI Solution', 'icon' => 'brain-circuit'],
            ['name' => 'Cloud Solution', 'icon' => 'cloud'],
            ['name' => 'Digital Transformation', 'icon' => 'workflow'],
        ] as $order => $service) {
            Service::updateOrCreate(['name' => $service['name']], [
                'description' => 'ออกแบบและพัฒนาโซลูชันดิจิทัลที่เหมาะกับเป้าหมายขององค์กร',
                'icon' => $service['icon'],
                'status' => 'active',
                'sort_order' => $order + 1,
            ]);
        }

        TeamMember::factory()->count(5)->create();
    }
}

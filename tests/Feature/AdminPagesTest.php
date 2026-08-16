<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_pages_require_authentication(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
        $this->get('/admin/projects')->assertRedirect('/login');
    }

    public function test_authenticated_admin_can_render_dashboard_and_four_module_shells(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user);

        $this->get('/admin/dashboard')->assertOk()->assertSee('ภาพรวมระบบ CMS')->assertSee('API driven');
        $this->get('/admin/projects')->assertOk()->assertSee('ผลงาน')->assertSee('data-content-table', false);
        $this->get('/admin/articles')->assertOk()->assertSee('ข่าวสาร')->assertSee('data-content-table', false);
        $this->get('/admin/services')->assertOk()->assertSee('บริการ')->assertSee('data-content-table', false);
        $this->get('/admin/team')->assertOk()->assertSee('ทีมงาน')->assertSee('data-content-table', false);
    }

    public function test_editor_can_access_content_shell_but_not_unauthenticated_pages(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'editor']));
        $this->get('/admin/projects/create')->assertOk()->assertSee('ข้อมูลจะถูกส่งเป็น JSON');
    }
}

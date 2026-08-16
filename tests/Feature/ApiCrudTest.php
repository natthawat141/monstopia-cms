<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\Project;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ApiCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    protected function api(): static
    {
        $token = Str::random(40);
        return $this->withSession(['_token' => $token])->withHeader('X-CSRF-TOKEN', $token);
    }

    public function test_public_project_collection_returns_json_envelope_and_category(): void
    {
        $category = Category::factory()->create(['name' => 'Blockchain & NFT']);
        $project = Project::factory()->create(['category_id' => $category->id, 'title' => 'BullMoonJR NFT', 'status' => 'published']);

        $this->getJson('/api/projects')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.0.title', $project->title)
            ->assertJsonPath('data.0.category.name', $category->name)
            ->assertJsonPath('meta.total', 1)
            ->assertJsonStructure(['success', 'message', 'data', 'meta']);
    }

    public function test_unauthenticated_write_returns_json_401(): void
    {
        $this->postJson('/api/services', [])->assertUnauthorized()->assertJson([
            'success' => false,
            'message' => 'Unauthenticated',
        ]);
    }

    public function test_admin_can_create_update_delete_project_via_json_api(): void
    {
        $this->actingAs($this->admin());
        $category = Category::factory()->create();
        $payload = [
            'category_id' => $category->id,
            'title' => 'BullMoonJR NFT',
            'slug' => 'bullmoonjr-nft',
            'description' => 'A structured project description',
            'client_name' => 'MONSTOPIA',
            'project_url' => 'https://example.com/project',
            'status' => 'draft',
        ];

        $created = $this->api()->postJson('/api/projects', $payload)
            ->assertCreated()
            ->assertJsonPath('data.slug', 'bullmoonjr-nft');
        $projectId = $created->json('data.id');

        $this->api()->putJson("/api/projects/{$projectId}", [...$payload, 'title' => 'BullMoonJR NFT Updated', 'status' => 'published'])
            ->assertOk()
            ->assertJsonPath('data.title', 'BullMoonJR NFT Updated')
            ->assertJsonPath('data.status', 'published');

        $this->getJson("/api/projects/{$projectId}")
            ->assertOk()
            ->assertJsonPath('data.category.id', $category->id);

        $this->api()->deleteJson("/api/projects/{$projectId}")
            ->assertOk()
            ->assertJsonPath('data', null);

        $this->assertDatabaseMissing('projects', ['id' => $projectId]);
    }

    public function test_admin_can_create_all_content_modules(): void
    {
        $this->actingAs($this->admin());
        $category = Category::factory()->create();
        $this->api()->postJson('/api/articles', ['category_id' => $category->id, 'title' => 'News', 'slug' => 'news', 'content' => 'Content', 'status' => 'published'])->assertCreated();
        $this->api()->postJson('/api/services', ['name' => 'AI Solution', 'description' => 'AI service', 'status' => 'active', 'sort_order' => 1])->assertCreated();
        $this->api()->postJson('/api/team-members', ['name' => 'Test Member', 'position' => 'Director', 'status' => 'active'])->assertCreated();
        $this->assertDatabaseCount('articles', 1);
        $this->assertDatabaseCount('services', 1);
        $this->assertDatabaseCount('team_members', 1);
    }

    public function test_project_validation_returns_spec_json_errors(): void
    {
        $this->actingAs($this->admin());
        $this->api()->postJson('/api/projects', ['title' => 'Incomplete'])
            ->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Validation failed')
            ->assertJsonStructure(['success', 'message', 'errors']);
    }

    public function test_dashboard_stats_returns_four_module_counts(): void
    {
        $this->actingAs($this->admin());
        Project::factory()->count(2)->create();
        Article::factory()->count(3)->create();
        Service::factory()->count(4)->create();
        TeamMember::factory()->count(5)->create();

        $this->getJson('/api/dashboard/stats')
            ->assertOk()
            ->assertJsonPath('data.projects', 2)
            ->assertJsonPath('data.news', 3)
            ->assertJsonPath('data.services', 4)
            ->assertJsonPath('data.team_members', 5);
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_filament_login_page_is_available(): void
    {
        $this->get('/admin/login')->assertOk();
    }

    public function test_admin_user_can_access_panel(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk();
    }

    public function test_connected_site_dashboard_links_to_its_cremona_workspace(): void
    {
        config()->set('maracuja.cremona.issuer', 'https://cremona.example.test');
        config()->set('maracuja.cremona.organization_slug', 'contempo-luthiers');
        $admin = User::factory()->create(['is_admin' => true]);

        Livewire::actingAs($admin)
            ->test(\App\Filament\Widgets\AdminWelcome::class)
            ->assertSee('Gestion commerciale')
            ->assertSee('https://cremona.example.test/dashboard/contempo-luthiers');
    }

    public function test_manager_user_can_access_panel_and_edit_content(): void
    {
        $manager = User::factory()->create(['role' => User::ROLE_MANAGER]);

        $this->assertTrue($manager->canAccessPanel(app(\Filament\Panel::class)));
        $this->assertTrue($manager->canEditContent());
    }

    public function test_viewer_cannot_access_sections_with_write_actions(): void
    {
        $viewer = User::factory()->create(['role' => User::ROLE_VIEWER]);

        $this->assertTrue($viewer->canAccessContent('pages'));
        $this->assertFalse($viewer->canAccessContent('inquiries'));
        $this->assertFalse($viewer->canAccessContent('audience'));
    }

    public function test_non_admin_user_cannot_access_panel(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get('/admin')
            ->assertForbidden();
    }
}

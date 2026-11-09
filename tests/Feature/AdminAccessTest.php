<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    public function test_manager_user_can_access_panel_and_edit_content(): void
    {
        $manager = User::factory()->create(['role' => User::ROLE_MANAGER]);

        $this->assertTrue($manager->canAccessPanel(app(\Filament\Panel::class)));
        $this->assertTrue($manager->canEditContent());
    }

    public function test_non_admin_user_cannot_access_panel(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get('/admin')
            ->assertForbidden();
    }
}

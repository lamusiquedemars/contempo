<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maracuja\SiteCore\ProfessionalWorkspace\Models\ProfessionalWorkspaceSetting;
use Maracuja\SiteCore\ProfessionalWorkspace\Support\ProfessionalWorkspace;
use Tests\TestCase;

class ProfessionalWorkspaceAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_contempo_exposes_one_configurable_workspace_entry(): void
    {
        config()->set('maracuja-professional-workspace.enabled', true);
        config()->set('maracuja-professional-workspace.default_label', 'Pilotage de l’activité');
        ProfessionalWorkspaceSetting::current()->update(['is_enabled' => true]);

        session()->put('cremona_portal.navigation', [['key' => 'workspace']]);
        $this->assertTrue(ProfessionalWorkspace::isAvailable());

        $admin = User::factory()->create(['role' => User::ROLE_ADMIN, 'is_admin' => true]);

        $this->actingAs($admin)
            ->withSession(['cremona_portal.navigation' => session('cremona_portal.navigation')])
            ->get('/admin')
            ->assertOk()
            ->assertSee('Pilotage de l’activité');

        $this->assertSame('Pilotage de l’activité', ProfessionalWorkspaceSetting::current()->label);
    }
}

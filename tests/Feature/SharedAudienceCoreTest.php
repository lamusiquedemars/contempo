<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\Modules;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maracuja\SiteCore\Audience\Models\AudienceContact;
use Tests\TestCase;

final class SharedAudienceCoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_shared_audience_routes_are_registered(): void
    {
        $this->assertSame(
            'Maracuja\\SiteCore\\Audience\\Http\\Controllers\\AudienceUnsubscribeController',
            app('router')->getRoutes()->getByName('audience.unsubscribe')->getAction('controller'),
        );
        $this->assertSame(
            'Maracuja\\SiteCore\\Brevo\\Http\\Controllers\\BrevoAudienceWebhookController',
            app('router')->getRoutes()->getByName('webhooks.brevo.audience')->getAction('controller'),
        );
    }

    public function test_unsubscribe_uses_the_shared_audience_model(): void
    {
        $contact = AudienceContact::query()->create([
            'email' => 'audience@example.test',
            'accepts_email' => true,
        ]);

        $this->get(route('audience.unsubscribe', $contact->unsubscribe_token))
            ->assertOk();

        $this->assertFalse($contact->fresh()->accepts_email);
    }

    public function test_admin_registers_the_shared_audience_workspace(): void
    {
        $this->assertTrue(Modules::installed('audience'));

        $admin = User::factory()->create(['is_admin' => true, 'role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk()
            ->assertSee('Communications');
    }
}

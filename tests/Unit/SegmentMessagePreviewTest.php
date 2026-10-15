<?php

namespace Tests\Unit;

use App\Modules\Audience\Models\AudienceContact;
use App\Modules\Audience\Models\AudienceSegment;
use App\Modules\Audience\Models\SegmentMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SegmentMessagePreviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_preview_does_not_render_a_real_unsubscribe_link(): void
    {
        $segment = AudienceSegment::query()->create(['name' => 'Clients en location']);

        $contact = AudienceContact::query()->create([
            'email' => 'alice@example.test',
            'accepts_email' => true,
        ]);

        $segment->contacts()->attach($contact);

        $message = SegmentMessage::query()->create([
            'audience_segment_id' => $segment->id,
            'subject' => 'Information',
            'body' => '<p>Bonjour les amis,</p>',
        ]);

        $html = view('filament.audience.segment-message-preview', [
            'segmentMessage' => $message,
        ])->render();

        $this->assertStringContainsString('Lien de désinscription masqué dans l’aperçu.', $html);
        $this->assertStringNotContainsString($contact->unsubscribe_token, $html);
    }

    public function test_preview_rewrites_public_storage_images_without_reuploading(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('campaigns/summer.jpg', 'image-bytes');

        $segment = AudienceSegment::query()->create(['name' => 'Clients']);

        $message = SegmentMessage::query()->create([
            'audience_segment_id' => $segment->id,
            'subject' => 'Information',
            'body' => '<p>Bonjour</p><img src="http://example.local/storage/campaigns/summer.jpg" alt="Atelier">',
        ]);

        $html = view('filament.audience.segment-message-preview', [
            'segmentMessage' => $message,
        ])->render();

        $this->assertStringContainsString(url('/storage/campaigns/summer.jpg'), $html);
        $this->assertSame(['campaigns/summer.jpg'], Storage::disk('public')->allFiles());
    }

    public function test_preview_converts_markdown_tables_and_paragraphs_to_html(): void
    {
        $segment = AudienceSegment::query()->create(['name' => 'Clients']);

        $message = SegmentMessage::query()->create([
            'audience_segment_id' => $segment->id,
            'subject' => 'Nouveaux tarifs',
            'body' => "Chères clientes, chers clients,\n\nLes tarifs évoluent :\n\n| Instrument | Location mensuelle |\n| - | - |\n| Violon enfant | 15 € |",
        ]);

        $html = view('filament.audience.segment-message-preview', [
            'segmentMessage' => $message,
        ])->render();

        $this->assertStringContainsString('<table', $html);
        $this->assertStringContainsString('>Violon enfant</td>', $html);
        $this->assertStringContainsString('>Chères clientes, chers clients,</p>', $html);
    }

    public function test_preview_reassembles_stacked_editor_table_cells(): void
    {
        $segment = AudienceSegment::query()->create(['name' => 'Clients']);

        $message = SegmentMessage::query()->create([
            'audience_segment_id' => $segment->id,
            'subject' => 'Nouveaux tarifs',
            'body' => "|   |\n| - |\n\nInstrument\n\n|   |\n| - |\n\nLocation mensuelle\n\n|   |\n| - |\n\nViolon enfant\n\n|   |\n| - |\n\n15 €\n\nLes tarifs seront appliqués en novembre.",
        ]);

        $html = view('filament.audience.segment-message-preview', [
            'segmentMessage' => $message,
        ])->render();

        $this->assertStringContainsString('>Instrument</th>', $html);
        $this->assertStringContainsString('>Location mensuelle</th>', $html);
        $this->assertStringContainsString('>Violon enfant</td>', $html);
        $this->assertStringContainsString('>15 €</td>', $html);
        $this->assertStringContainsString('>Les tarifs seront appliqués en novembre.</p>', $html);
    }

    public function test_preview_adds_inline_styles_to_html_tables_and_paragraphs(): void
    {
        $segment = AudienceSegment::query()->create(['name' => 'Clients']);

        $message = SegmentMessage::query()->create([
            'audience_segment_id' => $segment->id,
            'subject' => 'Nouveaux tarifs',
            'body' => '<p>Bonjour</p><table><tbody><tr><th>Instrument</th><th>Tarif</th></tr><tr><td>Violon</td><td>15 €</td></tr></tbody></table>',
        ]);

        $html = view('filament.audience.segment-message-preview', [
            'segmentMessage' => $message,
        ])->render();

        $this->assertStringContainsString('display:table-cell;', $html);
        $this->assertStringContainsString('margin:0 0 16px;', $html);
    }
}

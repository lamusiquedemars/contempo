<?php

namespace App\Modules\InstrumentProjection\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\InstrumentProjection\Models\PublishedInstrument;
use App\Modules\Pages\Models\Page;
use App\Modules\SiteSettings\Models\SiteSetting;
use App\Support\Modules;
use Illuminate\View\View;

class PublicInstrumentController extends Controller
{
    public function index(): View
    {
        abort_unless(Modules::enabled('pages'), 404);

        $page = Page::query()
            ->where('slug', 'instruments')
            ->where('is_published', true)
            ->firstOrFail();

        abort_if($page->isModule(), 404);

        return view('site.pages.instruments', [
            'settings' => SiteSetting::current(),
            'page' => $page,
            'contactUrl' => Modules::enabled('contact_form') ? route('contact') : null,
            'instruments' => PublishedInstrument::query()
                ->whereIn('availability', ['available', 'reserved'])
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function show(string $slug): View
    {
        return view('instruments.show', ['settings' => SiteSetting::current(), 'instrument' => PublishedInstrument::query()->where('slug', $slug)->whereIn('availability', ['available', 'reserved'])->firstOrFail()]);
    }
}

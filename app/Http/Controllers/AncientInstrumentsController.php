<?php

namespace App\Http\Controllers;

use App\Modules\Gallery\Models\Gallery;
use App\Modules\SiteSettings\Models\SiteSetting;
use App\Support\Modules;
use Illuminate\Contracts\View\View;

class AncientInstrumentsController extends Controller
{
    public function __invoke(): View
    {
        $gallery = Modules::enabled('gallery')
            ? Gallery::query()
                ->where('slug', 'instruments-anciens')
                ->where('is_published', true)
                ->first()
            : null;

        return view('site.pages.instruments-anciens', [
            'settings' => SiteSetting::current(),
            'contactUrl' => Modules::enabled('contact_form') ? route('contact') : null,
            'galleryImages' => $gallery
                ? $gallery->images()->where('is_published', true)->orderBy('position')->get()
                : collect(),
        ]);
    }
}

<?php

namespace App\Modules\InstrumentProjection\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\InstrumentProjection\Models\PublishedInstrument;
use App\Modules\SiteSettings\Models\SiteSetting;
use Illuminate\View\View;

class PublicInstrumentController extends Controller
{
    public function index(): View
    {
        return view('instruments.index', ['settings' => SiteSetting::current(), 'instruments' => PublishedInstrument::query()->whereIn('availability', ['available', 'reserved'])->orderBy('name')->get()]);
    }

    public function show(string $slug): View
    {
        return view('instruments.show', ['settings' => SiteSetting::current(), 'instrument' => PublishedInstrument::query()->where('slug', $slug)->whereIn('availability', ['available', 'reserved'])->firstOrFail()]);
    }
}

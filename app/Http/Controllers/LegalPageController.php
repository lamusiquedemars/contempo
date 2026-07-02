<?php

namespace App\Http\Controllers;

use App\Modules\SiteSettings\Models\SiteSetting;
use Illuminate\Contracts\View\View;

class LegalPageController extends Controller
{
    public function legal(): View
    {
        return view('site.legal', [
            'settings' => SiteSetting::current(),
        ]);
    }

    public function privacy(): View
    {
        return view('site.privacy', [
            'settings' => SiteSetting::current(),
        ]);
    }
}

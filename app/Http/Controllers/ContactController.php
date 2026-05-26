<?php

namespace App\Http\Controllers;

use App\Mail\ContactSubmissionReceived;
use App\Modules\Contact\Models\ContactSubmission;
use App\Modules\SiteSettings\Models\SiteSetting;
use App\Support\Modules;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function create(): View
    {
        abort_unless(Modules::enabled('contact'), 404);

        return view('site.contact', [
            'settings' => SiteSetting::current(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(Modules::enabled('contact'), 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['nullable', 'string', 'max:60'],
            'subject' => ['nullable', 'string', 'max:160'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $submission = ContactSubmission::query()->create($data);
        $settings = SiteSetting::current();

        if ($settings->contact_email) {
            Mail::to($settings->contact_email)->send(new ContactSubmissionReceived($submission));
        }

        return redirect()
            ->route('contact')
            ->with('status', 'Votre message a bien ete envoye.');
    }
}

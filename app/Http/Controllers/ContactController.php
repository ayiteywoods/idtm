<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('website.contact', [
            'siteName' => SiteSetting::get('site_name', config('app.name')),
            'contactEmail' => SiteSetting::get('contact_email', 'info@idtm.edu.gh'),
            'contactPhone' => SiteSetting::get('contact_phone', '+233 208 824 029; +233 555 371 028'),
            'contactAddress' => SiteSetting::get('contact_address', 'Office of the Registrar, P. O. Box DL 494, Adisadel, Cape Coast'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:120'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        return back()->with('status', 'Thank you for contacting us. Our admissions team will respond shortly.');
    }
}

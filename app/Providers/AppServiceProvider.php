<?php

namespace App\Providers;

use App\Support\PortalNavigation;
use App\Support\WebsiteNavigation;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.portal', function ($view) {
            $view->with('portalNav', PortalNavigation::resolve());

            if (auth()->check()) {
                $notificationCount = match (auth()->user()->role->value) {
                    'admin' => \App\Models\ChangeRequest::where('status', 'pending')->count(),
                    'student' => auth()->user()->studentProfile
                        ?->changeRequests()->where('status', 'pending')->count() ?? 0,
                    default => 0,
                };
                $view->with('notificationCount', $notificationCount);
            }
        });

        View::composer(['layouts.website', 'website.*', 'auth.login'], function ($view) {
            $view->with([
                'websiteNav' => WebsiteNavigation::mainNav(),
                'utilityNav' => WebsiteNavigation::utilityNav(),
                'footerLinks' => WebsiteNavigation::footerLinks(),
                'footerIntro' => \App\Models\SiteSetting::get('footer_intro', 'Shaping leaders in development policy, technology management, and innovation across Ghana and West Africa.'),
                'contactEmail' => \App\Models\SiteSetting::get('contact_email', 'info@idtm.edu.gh'),
                'contactPhone' => \App\Models\SiteSetting::get('contact_phone', '+233 208 824 029; +233 555 371 028'),
                'contactAddress' => \App\Models\SiteSetting::get('contact_address', 'Office of the Registrar, P. O. Box DL 494, Adisadel, Cape Coast'),
                'siteName' => \App\Models\SiteSetting::get('site_name', config('app.name')),
                'siteTagline' => \App\Models\SiteSetting::get('tagline', 'Knowledge and Excellence'),
            ]);
        });
    }
}

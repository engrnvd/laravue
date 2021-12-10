<?php

namespace App\Providers;

use App\Company;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\ServiceProvider;
use Naveed\Scaff\ScaffServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(IdeHelperServiceProvider::class);
            $this->app->register(ScaffServiceProvider::class);
        }
    }

    public function boot()
    {
        Company::created(function (Company $company) {
            $template = 'emails.company.new-company-email-template';
            app('mailer')->send($template, ['company' => $company], function ($m) use ($company) {
                $m->to($company->email)->subject("New Company: $company->name");
            });
        });
    }
}

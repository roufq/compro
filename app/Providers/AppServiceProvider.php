<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View as ViewContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();

        View::composer([
            'components.app-logo',
            'components.app-logo-icon',
            'components.layouts.auth.simple',
            'components.desktop-user-menu',
            'layouts.auth.simple',
            'layouts.admin',
            'partials.head',
        ], function (ViewContract $view): void {
            $view->with('siteSettings', SiteSetting::current());
        });

        View::composer('*', function (ViewContract $view): void {
            $viewPath = str_replace('\\', '/', $view->getPath());

            if (! str_ends_with($viewPath, '/resources/views/layouts/app/header.blade.php')
                && ! str_ends_with($viewPath, '/resources/views/layouts/app/sidebar.blade.php')) {
                return;
            }

            $view->with('siteSettings', SiteSetting::current());
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}

<?php

namespace Ybreaka98\EbtekarDCB;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class EbtekarDCBServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('ebtekardcb')
            ->hasConfigFile()
            ->hasViews()
            ->hasAssets()
            ->hasRoute('web')
            ->hasTranslations()
            ->hasMigrations();
    }

    public function packageBooted(): void
    {
        // Publish views
        $this->publishes([
            $this->package->basePath('/../resources/views') => resource_path('views/vendor/ebtekardcb'),
        ], 'ebtekardcb-views');

        // Publish assets (CSS, JS)
        $this->publishes([
            $this->package->basePath('/../resources/css') => public_path('css'),
            $this->package->basePath('/../resources/js') => public_path('js'),
        ], 'ebtekardcb-assets');

        // Publish config
        $this->publishes([
            $this->package->basePath('/../config/ebtekardcb.php') => config_path('ebtekardcb.php'),
        ], 'ebtekardcb-config');

        // Publish translations
        $this->publishes([
            $this->package->basePath('/../resources/lang') => resource_path('lang/vendor/ebtekardcb'),
        ], 'ebtekardcb-translations');
    }

    public function packageRegistered(): void
    {
        // Register the main EbtekarDCB service
        $this->app->singleton('ebtekardcb', function () {
            return new EbtekarDCB();
        });

        // Register view composers for common data
        $this->app->make('view')->composer('ebtekardcb::*', function ($view) {
            $locale = request('locale', app()->getLocale());

            // Set locale for the view
            $view->with('locale', $locale);

            // Add common configuration data
            $view->with('logoUrl', config('ebtekardcb.logo_url'));
            $view->with('serviceDescription', config('ebtekardcb.service_description'));
            $view->with('supportEmail', config('ebtekardcb.support_email'));
        });
    }
}

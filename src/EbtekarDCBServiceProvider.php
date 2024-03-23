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
            ->hasViews();
    }
}

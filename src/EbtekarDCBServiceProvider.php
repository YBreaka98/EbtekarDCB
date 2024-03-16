<?php

namespace Ybreaka98\EbtekarDCB;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Ybreaka98\EbtekarDCB\Commands\EbtekarDCBCommand;

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
//            ->hasMigration('create_ebtekardcb_table')
//            ->hasCommand(EbtekarDCBCommand::class);
    }
}

<?php

namespace Ybreaka98\EbtekarDCB\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Ybreaka98\EbtekarDCB\EbtekarDCBServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            EbtekarDCBServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_ebtekardcb_table.php.stub';
        $migration->up();
        */
    }
}

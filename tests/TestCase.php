<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Fortify\Features;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        // Force the in-memory sqlite test database before any trait hook runs.
        // If bootstrap/cache/config.php exists (e.g. after `php artisan optimize`),
        // Laravel loads that cached config and ignores the phpunit.xml <env> values,
        // which would otherwise point tests at the development PostgreSQL database
        // and wipe it via RefreshDatabase's migrate:fresh.
        if ($this->app === null) {
            $this->refreshApplication();
        }

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');

        parent::setUp();
    }

    protected function skipUnlessFortifyHas(string $feature, ?string $message = null): void
    {
        if (! Features::enabled($feature)) {
            $this->markTestSkipped($message ?? "Fortify feature [{$feature}] is not enabled.");
        }
    }
}

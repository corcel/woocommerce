<?php
declare(strict_types=1);

namespace Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    /**
     * @inheritDoc
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom([
            '--database' => 'wp',
            '--realpath' => true,
            '--path'     => __DIR__ . '/database/migrations',
        ]);

        $this->withFactories(__DIR__ . '/database/factories');
    }

    /**
     * @inheritDoc
     *
     * @param   \Illuminate\Foundation\Application  $app
     * @return  void
     */
    protected function getEnvironmentSetUp($app): void
    {
        $this->configureDatabaseConfig($app);
    }

    /**
     * Configure database.
     *
     * @param   \Illuminate\Foundation\Application  $app
     * @return  void
     */
    private function configureDatabaseConfig($app): void
    {
        $app['config']->set('database.connections.wp', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => 'wp_',
        ]);

        $app['config']->set('database.default', 'wp');
    }
}

<?php

namespace MDM23\LaravelAdminer;

use Illuminate\Support\ServiceProvider;
use RuntimeException;

class AdminerServiceProvider extends ServiceProvider
{
    /**
     * Absolute path to the packages resource folder.
     */
    const RESOURCE_DIR = __DIR__ . "/../../resources";

    /**
     * Absolute path to the default config file.
     */
    const CONFIG_FILE  = self::RESOURCE_DIR . "/config.php";

    /**
     * Absolute path to the file containing the route definition of this
     * package.
     */
    const ROUTES_FILE  = self::RESOURCE_DIR . "/routes.php";

    /**
     * The minor version number of the current Laravel installation. This is
     * used for backward compatibility of this service provider.
     *
     * @var int
     */
    protected $minorVersion;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->parseLaravelVersion($this->app->version());

        $this->publishes([ self::CONFIG_FILE => $this->getConfigPath() ]);

        $this->loadRoutes();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(self::CONFIG_FILE, "laravel-adminer");
    }

    /**
     * The method registers the routes of the package to Laravel.
     *
     * @return void
     */
    protected function loadRoutes()
    {
        if ($this->minorVersion >= 3) {
            $this->loadRoutesFrom(self::ROUTES_FILE);
            return;
        }

        if (! $this->app->routesAreCached()) {
            require self::ROUTES_FILE;
        }
    }

    /**
     * Parses the given Laravel version and stores the minor version in the
     * class attribute `minorVersion` to be used during bootstrap.
     *
     * @param  string $version
     * @return void
     */
    protected function parseLaravelVersion(string $version)
    {
        if (!preg_match("/^(Lumen \()?5\.(?P<minor>\d+)\./", $version, $matches)) {
            throw new RuntimeException(
                "Unable to parse Laravel minor version: " . $version
            );
        }

        $this->minorVersion = (int)$matches["minor"];
    }

    /**
     * Returns the path where the config file is expected to be stored in the
     * Laravel / Lumen installation.
     *
     * @return string
     */
    protected function getConfigPath()
    {
        if (!function_exists("config_path")) {
            return base_path("config/laravel-adminer.php");
        }

        return config_path("laravel-adminer.php");
    }
}

<?php

namespace MDM23\LaravelAdminer;

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AdminerController extends Controller
{
    /**
     * @var \Illuminate\Config\Repository
     */
    private $config;

    /**
     * The path to access the database configuration.
     *
     * @var string
     */
    private $configPrefix;

    /**
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * Creates a new instance of AdminerController.
     *
     * @param \Illuminate\Config\Repository $config
     * @param \Illuminate\Http\Request      $request
     */
    public function __construct(ConfigRepository $config, Request $request)
    {
        $this->config  = $config;
        $this->request = $request;

        $this->configPrefix = (
            "database.connections.{$this->config->get("database.default")}."
        );
    }

    /**
     * This method is called when the configured route of adminer is accessed.
     * We perform an automatic login (if configured) and load the actual adminer
     * file afterwards.
     */
    public function index()
    {
        $this->autoLogin();

        require __DIR__ . "/../../resources/adminer.php";
    }

    /**
     * Sets the POST parameters required to login to the default database. This
     * is only done when the configuration option laravel-adminer.autoLogin is
     * set to true. If the config contains an empty password, the user propably
     * uses socket authentication. As adminer prevents empty passwords, we
     * generate a random one for this purpose.
     */
    private function autoLogin()
    {
        if (! $this->config->get("laravel-adminer.autoLogin")) {
            return;
        }

        if ($this->request->has("username")) {
            return;
        }

        $_POST["auth"] = [
            "db"       => $this->config("database"),
            "driver"   => $this->mapDriver($this->config("driver")),
            "password" => $this->config("password", str_random(16)),
            "server"   => $this->config("host"),
            "username" => $this->config("username"),
        ];
    }

    /**
     * Returns the actual driver identifier, that should be passed to adminer.
     *
     * @param  string $driver
     * @return string
     */
    private function mapDriver(string $driver): string
    {
        if ($driver === "mysql") {
            return "server";
        }
        return $driver;
    }

    /**
     * Gets a value from the default database configuration. For empty values,
     * the $default parameter is returned instead.
     *
     * @param  string $path
     * @param  string $default
     * @return mixed
     */
    private function config(string $path, $default = null)
    {
        return $this->config->get($this->configPrefix . $path) ?: $default;
    }
}

<?php

require __DIR__ . "/../vendor/autoload.php";

use HeadlessChromium\BrowserFactory;
use Symfony\Component\Process\Process;
use Webmozart\Assert\Assert;

if (!function_exists("env")) {
    /**
     * Helper function to retrieve the value of an environment variable. If the
     * variable is not set, the value of the $default parameter is returned
     * instead.
     *
     * @param  string $var
     * @param  string $default
     * @return string
     */
    function env(string $var, string $default = null) {
        return getenv($var) ?: $default;
    }
}

/**
 * The function is registered as a shutdown function. It assures that the
 * spawned browser gets closed and the web server is shut down after the test
 * routines have ended.
 */
function cleanup(): void {
    global $browser, $server;
    echo "---" . PHP_EOL;

    if (isset($browser)) {
        echo "Closing browser ... ";
        $browser->close();
        echo "done" . PHP_EOL;
    }

    if (isset($server) && $server->isRunning()) {
        echo "Stopping web server ... ";
        $server->stop();
        echo "done" . PHP_EOL;
    }
}

/**
 * The functions checks that there is no error message from adminer on the
 * currently active page. Adminer displays error messages in a div container
 * with the class "error". If an error is found, the message is written to
 * STDOUT and the tests are aborted.
 */
function assertNoError(): void {
    global $page;

    $evaluation = $page->evaluate(<<<EOT
        (() => {
            if (el = document.querySelector(".error")) {
                return el.innerHTML;
            }
        })()
EOT);

    if (!empty($evaluation->getReturnValue())) {
        echo "UNEXPECTED ERROR: " . $evaluation->getReturnValue() . PHP_EOL;
        exit 1;
    }
}

register_shutdown_function("cleanup");

echo "Starting web server ... " . PHP_EOL;
$server = new Process(["php", "-S", "127.0.0.1:8000", "server.php"]);
$server->setWorkingDirectory(__DIR__ . "/../tmp/laravel");
$server->start();

$browserFactory = new BrowserFactory(env("CHROME_BINARY", "chromium"));

echo "Opening browser ... " . PHP_EOL;

$browser = $browserFactory->createBrowser([
    "headless"    => true,
    "customFlags" => [ "--disable-gpu" ],
    "windowSize"  => [ 960, 960 ],
    "sendSyncDefaultTimeout" => 10000,
]);

echo "Opening page ... " . PHP_EOL;

$page = $browser->createPage();
$page->setCookies([]);

echo "---" . PHP_EOL;

foreach (glob(__DIR__ . "/cases/*.php") as $file) {
    echo "Running test " . basename($file) . " ... ";
    require $file;
    echo "success" . PHP_EOL;
}

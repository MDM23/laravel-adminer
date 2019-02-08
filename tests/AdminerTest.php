<?php

use MDM23\LaravelAdminer\AdminerServiceProvider;

if (!class_exists("TestCase")) {
    class_alias("Tests\\TestCase", "TestCase");
}

class AdminerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        if (null === $this->app->getProvider(AdminerServiceProvider::class)) {
            $this->app->register(AdminerServiceProvider::class);
        }
    }

    /**
     * @backupGlobals disabled
     * @preserveGlobalState disabled
     */
    public function testExample()
    {
        $version = $this->app->version();

        if (!preg_match("#^5\.(\d+)\.#", $version, $m)) {
            throw new RuntimeException("Could not parse Laravel version: " . $version);
        }

        switch ($m[1]) {
            case "6":
                $this->runWithLaravel_5_6();
                break;
        }
    }

    private function runWithLaravel_5_6()
    {
        $response = $this->get("/adminer");
        $response->assertSee("Adminer");
    }
}

<?php

use Webmozart\Assert\Assert;

$username = env("DB_USERNAME");
$password = env("DB_PASSWORD");

$loginAction = $page->evaluate(<<<EOT
    (() => {
        document.querySelector("#username").value = "{$username}";
        document.querySelector("[name='auth[password]'").value = "{$password}";
        document.querySelector("form").submit();
    })()
EOT);

$loginAction->waitForPageReload();

$screenshot = $page->screenshot();
$screenshot->saveToFile(__DIR__ . "/../../tmp/home.png");
assertNoError();

Assert::eq($page->evaluate("document.title")->getReturnValue(), "Select database - Adminer");
$title = $page->evaluate("document.querySelector('#h1').innerHTML");
Assert::eq("Adminer", $title->getReturnValue());

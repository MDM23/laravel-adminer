<?php

use Webmozart\Assert\Assert;

$page->navigate("http://localhost:8000/adminer")->waitForNavigation();

$screenshot = $page->screenshot();
$screenshot->saveToFile(__DIR__ . "/../../tmp/login.png");

Assert::eq($page->evaluate("document.title")->getReturnValue(), "Login - Adminer");
assertNoError();

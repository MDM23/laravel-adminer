<?php

Route::addRoute(
    ["GET", "HEAD", "POST", "PUT", "PATCH", "DELETE", "OPTIONS"],
    config("laravel-adminer.baseURI"),
    \MDM23\LaravelAdminer\AdminerController::class . "@index"
);

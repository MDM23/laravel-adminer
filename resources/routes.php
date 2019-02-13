<?php

Route::any(
    config("laravel-adminer.baseURI"),
    \MDM23\LaravelAdminer\AdminerController::class . "@index"
);

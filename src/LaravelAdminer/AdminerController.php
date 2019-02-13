<?php

namespace MDM23\LaravelAdminer;

use Illuminate\Routing\Controller;

class AdminerController extends Controller
{
    public function index()
    {
        require __DIR__ . "/../../resources/adminer.php";
    }
}

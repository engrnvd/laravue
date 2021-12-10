<?php

Route::get('/', function () {
    return view('welcome');
});

require_once __DIR__ . "/crud-routes.php";
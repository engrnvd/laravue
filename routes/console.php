<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('create-fake-users', function () {
    factory(\App\User::class, 3)->create();
});

Artisan::command('test-bkg-progress', function () {
    $progress = new \App\Helpers\BackgroundTasks\Progress("Another very important task", 30, "609a4b5cfc166b77aa225cf6");

    for ($i = 1; $i <= 30; $i++) {
        $progress->log("Step {$i} is done.", true);
        sleep(1);
    }
});

Artisan::command('testing', function () {
    $city = \App\City::where('name', 'like', 'islAm')->get();
    echo to_str($city->toArray());
});


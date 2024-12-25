<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Artisan::command('make:repository {name}', function ($name) {
    $this->call(\App\Console\Commands\MakeRepository::class, ['name' => $name]);
})->describe('Create a new interface and repository');

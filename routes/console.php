<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Artisan::command('delete:expired-tokens', function () {
    DB::table('personal_access_tokens')->where('expires_at', '<', now())->delete();
})->purpose('Delete expired personal access tokens')->everyFifteenMinutes();
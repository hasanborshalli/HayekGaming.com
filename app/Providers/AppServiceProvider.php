<?php

namespace App\Providers;

use Illuminate\Database\Connection;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
{
    if (env('DB_LOG_QUERIES')) {
        DB::listen(function ($query) {

            $threshold = (int) env('DB_LOG_THRESHOLD_MS', 200);

            if ($query->time >= $threshold) {

                $req = request();

                Log::channel('single')->warning('SLOW QUERY', [
                    'time_ms'   => $query->time,
                    'sql'       => $query->sql,
                    'bindings'  => $query->bindings,

                    // 🔥 added context
                    'url'       => $req?->fullUrl(),
                    'method'    => $req?->method(),
                    'ip'        => $req?->ip(),
                    'user_id'   => optional($req?->user())->id,
                ]);
            }
        });
    }
}
}

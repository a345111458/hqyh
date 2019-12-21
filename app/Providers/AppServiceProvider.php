<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        DB::listen(function ($query) {
            $sql = $query->sql;
            $bindings = $query->bindings;
            $time = $query->time;
            //写入sql
            if ($bindings) {
                file_put_contents('.sqls', "[" . date("Y-m-d H:i:s") . "]" . $sql . "parmars:" . json_encode($bindings, 320) . "\r\n", FILE_APPEND);
            } else {
                file_put_contents('.sqls', "[" . date("Y-m-d H:i:s") . "]" . $sql . "\r\n\r\n", FILE_APPEND);
            }
        });
        //
    }
}

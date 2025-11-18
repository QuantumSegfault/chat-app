<?php

namespace App\Providers;

use App\Database\Query\Grammars\MariaDbGrammar;
use Illuminate\Support\Facades\DB;
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
        $connection = DB::connection('mariadb'); // or DB::connection() for default
        $connection->setQueryGrammar(new MariaDbGrammar($connection));
    }
}

<?php

namespace App\Providers;

use App\Models\Option;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services
     *
     * @return void
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        $this->enableSqlQueryLogging(false);
        $this->enableHttps(false);
        $this->fetchAdminOptionsFromDb();
    }

    private function fetchAdminOptionsFromDb(): ?array
    {
        $admin_options = cache()->rememberForever('admin_options', function (): ?array {
            try {
                $options = Option::get();

                return [
                    'registration' => $options->where('option', 'registration')->first()->value,
                    'men_category' => $options->where('option', 'men_category')->first()->value,
                    'women_category' => $options->where('option', 'women_category')->first()->value,
                ];
            } catch (QueryException $e) {
                no_connection_error($e, __CLASS__);
                return null;
            }
        });

        view()->share(compact('admin_options'));

        return null;
    }

    /**
     * @codeCoverageIgnore
     * @param bool $enable
     * @return void
     */
    private function enableHttps(bool $enable): void
    {
        if (app()->env === 'production' && $enable) {
            URL::forceScheme('https');
        }
    }

    /**
     * Method for debugging sql queries
     *
     * @codeCoverageIgnore
     * @param bool $enable
     * @return void
     */
    private function enableSqlQueryLogging(bool $enable): void
    {
        if (app()->env == 'local' && $enable) {
            DB::listen(function ($query) {
                dump($query->sql);
                // dump($query->time);
                // dump($query->bindings);
                dump('__________________________________________');
            });
        }
    }
}

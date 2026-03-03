<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        // 1. Run your default configurations
        $this->configureDefaults();

        // 2. Only share categories if we aren't running a command (like build or migrate)
        // and only if the categories table actually exists in the database yet.
        if (!app()->runningInConsole()) {
            if (Schema::hasTable('categories')) {
                View::share('allCategories', Category::all());
            }
        }
    }

    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );
    }
}
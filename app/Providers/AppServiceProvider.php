<?php

namespace App\Providers;

use App\View\Components\Dashboard\GeoLocationPicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (app()->isLocal()) {
            app()->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    public function boot(): void
    {
        $this->bootingLaraConfig();
        $this->bootingBladeComponents();
        $this->bootingMacros();
    }

    private function bootingMacros()
    {
        Builder::macro('active', function (string $attribute = 'status', bool $value = true) {
            return $this->where($attribute, $value);
        });

        // adding whereLike method to Builder (For Searching)
        Builder::macro('whereLike', function (string|array $attributes, string $searchTerm) {
            $this->where(function (Builder $query) use ($attributes, $searchTerm) {
                foreach ((array) $attributes as $attribute) {
                    $query->when(
                        str_contains($attribute, '.'),
                        function (Builder $query) use ($attribute, $searchTerm) {
                            [$relationName, $relationAttribute] = explode('.', $attribute);

                            $query->whereHas($relationName, function (Builder $query) use ($relationAttribute, $searchTerm) {
                                $query->orWhere($relationAttribute, 'LIKE', "%{$searchTerm}%");
                            });
                        },
                        function (Builder $query) use ($attribute, $searchTerm) {
                            $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                        }
                    );
                }
            });

            return $this;
        });
    }

    private function bootingBladeComponents()
    {
        Blade::component('dashboard.geo-location-picker', GeoLocationPicker::class);
    }

    private function bootingLaraConfig()
    {
        Paginator::useBootstrap();
        Model::preventLazyLoading(
            !$this->app->isProduction()
        );
    }
}

<?php

namespace JoeDixon\Translation;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class TranslationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViews();

        $this->registerRoutes();

        $this->publishConfiguration();

        $this->publishAssets();

        $this->loadMigrations();

        $this->loadTranslations();

        $this->registerCommands();
    }

    /**
     * Register package bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfiguration();

        $this->registerContainerBindings();
    }

    /**
     * Load and publish package views.
     *
     * @return void
     */
    private function loadViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'translation');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/translation'),
        ]);
    }

    /**
     * Register package routes.
     *
     * @return void
     */
    private function registerRoutes()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    /**
     * Publish package configuration.
     *
     * @return void
     */
    private function publishConfiguration()
    {
        $this->publishes([
            __DIR__ . '/../config/translation.php' => config_path('translation.php'),
        ], 'config');
    }

    /**
     * Merge package configuration.
     *
     * @return void
     */
    private function mergeConfiguration()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/translation.php', 'translation');
    }

    /**
     * Publish package assets.
     *
     * @return void
     */
    private function publishAssets()
    {
        $this->publishes([
            __DIR__ . '/../resources/assets' => public_path('vendor/translation'),
        ], 'assets');
    }

    /**
     * Load package migrations.
     *
     * @return void
     */
    private function loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    /**
     * Load package translations.
     *
     * @return void
     */
    private function loadTranslations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'translation');

        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/translation'),
        ]);
    }

    /**
     * Register package commands.
     *
     * @return void
     */
    private function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                // Commands will go here
            ]);
        }
    }

    /**
     * Register package bindings in the container.
     *
     * @return void
     */
    private function registerContainerBindings()
    {
        $this->app->singleton(Scanner::class, function () {
            $config = $this->app['config']['translation'];
            return new Scanner(new Filesystem, $config['scan_paths'], $config['translation_methods']);
        });
    }
}
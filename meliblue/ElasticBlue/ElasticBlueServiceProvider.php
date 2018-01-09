<?php namespace Meliblue\ElasticBlue;

use Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;

class ElasticBlueServiceProvider extends ServiceProvider
{
    /**
     * @inheritdoc
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/elasticblue.php' => config_path('elasticblue.php'),
        ], 'config');
    }

    /**
     * @inheritdoc
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/elasticblue.php', 'elasticblue'
        );

        $this->app->singleton('elasticblue', function () {
            return ClientBuilder::fromConfig(config('elasticblue.connection'));
        });
    }

    /**
     * @inheritdoc
     */
    public function provides()
    {
        return ['elasticblue'];
    }
}

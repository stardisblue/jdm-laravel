<?php namespace Meliblue\ElasticBlue\Facade;

use Illuminate\Support\Facades\Facade as Facade;

class Es extends Facade
{
    /**
     * @inheritdoc
     */
    protected static function getFacadeAccessor()
    {
        return 'elasticblue';
    }
}
<?php

namespace Meliblue\ElasticBlue\Facade;

use Illuminate\Support\Facades\Facade as Facade;

/**
 * Class Es
 * @package Meliblue\ElasticBlue\Facade
 * @method static bulk($params)
 * @method static index($params)
 * @method static get($params)
 * @method static delete($params)
 * @method static deleteByQuery($params = array())
 * @method static create($params);
 * @method static search($params = array())
 */
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
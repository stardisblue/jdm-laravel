<?php
/**
 * Created by PhpStorm.
 * User: MELY
 * Date: 9/28/2017
 * Time: 5:39 PM
 */

namespace App;


use Isswp101\Persimmon\DAL\ElasticsearchDAL;
use Isswp101\Persimmon\DAL\IDAL;
use Isswp101\Persimmon\ElasticsearchModel as Model;

class NodeJDM extends Model
{
    public function __construct(array $attributes = [])
    {
        $dal = new ElasticsearchDAL($this, app(Client::class), app(EventEmitter::class));

        parent::__construct($dal, $attributes);
    }

    public static function createInstance()
    {
        return new static();
    }

}
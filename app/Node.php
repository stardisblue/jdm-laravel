<?php

namespace app;

use Elasticquent\ElasticquentTrait;
use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    use ElasticquentTrait;

    public $id;
    public $name;
    public $formatedName;
    public $type;
    public $weight;
}
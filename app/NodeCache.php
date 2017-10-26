<?php

namespace App;

use Elasticquent\ElasticquentTrait;
use Illuminate\Database\Eloquent\Model;

class NodeCache extends Node
{
    protected $table = "node_cache";
    public $relationTypes = [];
    public $nodeTypes = [];
}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    use ElasticquentTrait;

    public $id;
    public $source_node;
    public $node;
    public $type;
    public $weight;
}

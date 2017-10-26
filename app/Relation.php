<?php

namespace App;

use Elasticquent\ElasticquentTrait;
use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    use ElasticquentTrait;

    public $id;
    public $idRelationType;
    public $idRelation;
    public $weight;
    public $node;
}

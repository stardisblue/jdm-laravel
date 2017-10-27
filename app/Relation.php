<?php

namespace App;

use Meliblue\ElasticBlue\ElasticBlueModel;

class Relation extends ElasticBlueModel
{
    protected static $index = 'relations';

    public $id;
    public $idRelationType;
    public $idRelation;
    public $weight;
    public $node;
}

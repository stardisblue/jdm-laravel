<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    public $id;
    public $name;
    public $description;
    public $formattedName;
    public $weight;
    public $nodeType;
}
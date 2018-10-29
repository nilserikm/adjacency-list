<?php namespace App;

use Franzose\ClosureTable\Models\Entity;

class node extends Entity implements nodeInterface
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nodes';

    /**
     * ClosureTable model instance.
     *
     * @var nodeClosure
     */
    protected $closure = 'App\nodeClosure';
}

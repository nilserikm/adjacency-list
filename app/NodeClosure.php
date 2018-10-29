<?php namespace App;

use Franzose\ClosureTable\Models\ClosureTable;

class nodeClosure extends ClosureTable implements nodeClosureInterface
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'node_closure';
}

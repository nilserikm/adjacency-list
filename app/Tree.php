<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Tree extends Model
{
    public static function duplicate(\App\node $base, Collection $children)
    {
        foreach($children as $child) {
            $clone = $child->replicate();
            $base->addChild($clone);

            if ($child->hasChildren()) {
                Tree::duplicate($clone, $child->getChildren());
            }
        }

        return $base;
    }
}

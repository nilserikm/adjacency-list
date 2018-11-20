<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use \App\node;

class Tree extends Model
{
    /**
     * Returns all the trees belonging to the given company
     * @param $roots
     * @return array
     */
    public static function getTrees($roots)
    {
        $trees = [];

        foreach ($roots as $root) {
            $tree = $root->getDescendantsTree();
            array_push($trees, [
                'rootId' => $root->id,
                'descendants' => $tree
            ]);
        }

        return $trees;
    }

    /**
     * If children is empty then the base-node is returned, otherwise the base-
     * node is returned with a clone of the children attached
     * @param node $base
     * @param Collection $children
     * @return node
     */
    public static function duplicate(node $base, Collection $children)
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

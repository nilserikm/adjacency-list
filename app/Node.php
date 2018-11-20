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

    /**
     * Returns a random node from a randomly chosen tree
     * @param int $companyId
     * @return node $random
     */
    public static function getRandom(int $companyId)
    {
        $count = self::getCount($companyId);
        return node::where('company_id', $companyId)->get()[rand(0, ($count - 1))];
    }

    /**
     * Returns the count of the nodes in the trees in the array. If the array is
     * empty then all of the company's trees will be fetched and the the count
     * will be done
     * @param int $companyId
     * @return int
     */
    public static function getCount(int $companyId)
    {
        return node::where('company_id', $companyId)->count();
    }

    /**
     * Returns the node's path in the tree
     * @param node $node
     * @return array|mixed|string
     */
    public static function getPath(node $node, int $companyId)
    {
        if (empty($node->parent_id)) {
            $path = $node->title;
        } else {
            $path = [];
            array_unshift($path, $node->title);

            if (!is_null($node->parent_id)) {
                $traverse = function($base, &$array, &$companyId) use (&$traverse) {
                    if (!is_null($base->parent_id)) {
                        $parent = node::where('id', $base->parent_id)
                            ->where('company_id', $companyId)
                            ->first();
                        array_unshift($array, $parent->title);
                        $traverse($parent, $array, $companyId);
                    }

                    return $array;
                };

                $path = $traverse($node, $path, $companyId);
            }

            $path = implode(" > ", $path);
        }

        return $path;
    }
}

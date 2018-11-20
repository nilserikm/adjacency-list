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
     * @param array|null $trees
     * @return int
     */
    public static function getCount(int $companyId, array $trees = null)
    {
        $count = 0;
        if (empty($trees)) {
            $trees = Tree::getTrees($companyId);
        }

        foreach ($trees as $tree) {
            $flattened = $tree->toArray();
            $count += count($flattened);
        }

        return $count;
    }
}

<?php namespace App\Http\Controllers;

use \App\node;
use \App\Tree;
use Illuminate\Http\Request;

class NodeController extends Controller
{
    public $rootId = 1;
    public $companyId = 1;

    /**
     * Copies a node (with subtree) and appends the copy to the given parent
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function copyNode(Request $request)
    {
        $start = microtime(true);
        $success = false;
        $httpCode = 500;

        $root = node::where('id', $this->rootId)
            ->where('company_id', $this->companyId)
            ->first();
        $node = node::where('id', $request->input('nodeId'))
            ->where('company_id', $this->companyId)
            ->first();
        $parent = node::where('id', $request->input('parentId'))
            ->where('company_id', $this->companyId)
            ->first();

        if (empty($root)) {
            $message = "Root not found (" . $this->rootId . ")";
        } else if (empty($node)) {
            $message = "Node not found (" . $request->input('nodeId') . ")";
        } else if (empty($parent)) {
            $message = "Parent not found (" . $request->input('parentId') . ")";
        } else {
            $copy = $node->replicate();
            $copy->save();

            if ($node->hasChildren()) {
                $duplicateTimeStart = microtime(true);
                $copy = Tree::duplicate($copy, $node->getChildren());
                $duplicateTime = microtime(true) - $duplicateTimeStart;
            }

            $parent->addChild($copy);
            $time = microtime(true) - $start;

            $success = true;
            $message = "Node copied (" . $node->id . ") ";
            $message .= "new id (" . $copy->id . "), ";
            $message .= "appended to (" . $parent->id . ")";
            $httpCode = 200;
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'allCount' => $this->getCount(),
            'treeCount' => ($root->countDescendants() + 1),
            'time' => $time,
            'node' => !empty($node) ? $node : null,
            'duplicateTime' => $duplicateTime
        ], $httpCode);
    }

    /**
     * Appends a new, empty node to the given nodeId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function appendNode(Request $request)
    {
        $start = microtime(true);
        $success = false;
        $httpCode = 500;
        $root = node::where('id', $this->rootId)
            ->where('company_id', $this->companyId)
            ->first();
        $parent = node::where('id', $request->input('nodeId'))
            ->where('company_id', $this->companyId)
            ->first();

        if (empty($root)) {
            $message = "Unable to find root ...";
        } else if (empty($parent)) {
            $message = "Parent not found (" . $request->input('nodeId') . ")";
        } else {
            $node = new node();
            $node->title = "New append node ...";
            $node->company_id = $this->companyId;
            $parent->addChild($node);

            $success = true;
            $httpCode = 200;
            $message = "Empty node appended";
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'allCount' => $this->getCount(),
            'time' => microtime(true) - $start,
            'node' => !empty($node) ? $node : null
        ], $httpCode);
    }

    public function getRandomLeaf(node $node)
    {
        if ($node->hasChildren()) {
            $child = $node->getChildren()[rand(0, ($node->getChildren()->count() - 1))];
            return $this->getRandomLeaf($child);
        }

        return $node;
    }

    public function randomLeaf(Request $request)
    {
        $start = microtime(true);
        $success = false;
        $httpCode = 500;
        $root = node::find(1);

        if (empty($root)) {
            $message = "Unable to find root ...";
        } else {
            $node = $this->getRandomLeaf($root);

            if (is_null($node->parent_id)) {
                $node->path = $node->title;
            } else {
                $node->path = $this->getPath($node);
            }

            $message = "Random leaf fetched (" . $node->id . ")";
            $success = true;
            $httpCode = 200;
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'allCount' => $this->getCount($root),
            'time' => microtime(true) - $start,
            'node' => !empty($node) ? $node : null
        ], $httpCode);
    }

    public function getRandomNode($node = null)
    {
        if (is_null($node)) {
            $node = node::find(rand(1, node::count()));
            return $this->getRandomNode($node);
        }

        return $node;
    }

    public function getPath($node)
    {
        $path = [];
        array_unshift($path, $node->title);

        if (!is_null($node->parent_id)) {
            $traverse = function($base, &$array) use (&$traverse) {
                if (!is_null($base->parent_id)) {
                    $parent = node::find($base->parent_id);
                    array_unshift($array, $parent->title);
                    $traverse($parent, $array);
                }

                return $array;
            };

            $path = $traverse($node, $path);
        }

        return implode(" > ", $path);
    }

    public function randomNode(Request $request)
    {
        $start = microtime(true);
        $success = false;
        $httpCode = 500;
        $root = node::find(1);

        if (empty($root)) {
            $message = "Unable to find root ...";
        } else {
            $node = $this->getRandomNode();

            if (is_null($node->parent_id)) {
                $node->path = $node->title;
            } else {
                $node->path = $this->getPath($node);
            }

            $message = "Random node fetched (" . $node->id . ")";
            $success = true;
            $httpCode = 200;
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'allCount' => $this->getCount($root),
            'time' => microtime(true) - $start,
            'node' => !empty($node) ? $node : null
        ], $httpCode);
    }

    /**
     * Deletes a node and its descendants
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteById(Request $request)
    {
        $start = microtime(true);
        $success = false;
        $httpCode = 500;
        $root = node::where('id', $this->rootId)
            ->where('company_id', $this->companyId)
            ->first();
        $node = node::where('id', $request->input('deleteId'))
            ->where('company_id', $this->companyId)
            ->first();

        if (empty($root)) {
            $message = "root does not exist";
        } else if (is_null($request->input('deleteId'))) {
            $message = "node id cannot be null";
        } else if (is_null($node)) {
            $message = "Node not found (" . $request->input('deleteId') . ") ...";
        } else {
            try {
                $node->deleteSubtree(true);

                $success = true;
                $httpCode = 200;
                $message = "Node deleted ...";
            } catch(\Exception $exception) {
                $message = $exception->getMessage();
                $httpCode = $exception->getCode();
            }
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'allCount' => $this->getCount($root),
            'deleteId' => $request->input('deleteId'),
            'time' => microtime(true) - $start,
            'node' => !empty($node) ? $node : null
        ], $httpCode);
    }

    /**
     * Returns the count of nodes in the tree, including the root
     * @param node $root
     * @return int
     */
    public function getCount()
    {
        return node::count();
    }

    public function getTrees()
    {
        $trees = [];
        $roots = node::getRoots();
        foreach ($roots as $root) {
            $instance = $root->getTree();
            array_push($trees, $instance);
        }

        return $trees;
    }

    /**
     * Returns the node tree
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchTree(Request $request)
    {
        $start = microtime(true);
        $success = false;
        $httpCode = 500;

        $root = node::where('id', $this->rootId)
            ->where('company_id', $this->companyId)
            ->first();

        if (empty($root)) {
            $message = "root does not exist";
        } else {
            $tree = $root->getDescendantsTree();
            $success = true;
            $message = "Tree data fetched ...";
            $httpCode = 200;
        }

        $time = microtime(true) - $start;

        return response()->json([
            'success' => $success,
            'message' => $message,
            'root' => !empty($root) ? $root : null,
            'tree' => !empty($tree) ? $tree : null,
            'treeCount' => ($root->countDescendants() + 1),
            'allCount' => $this->getCount($root),
            'time' => $time
        ], $httpCode);
    }
}

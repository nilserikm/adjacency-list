<?php

namespace App\Http\Controllers;

use App\node;
use App\Tree;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class WelcomeController extends Controller
{
    public $rootId = 1;
    public $companyId = 1;

    public function copyNode(Request $request)
    {
        $start = microtime(true);
        $success = false;
        $httpCode = 500;
        $root = \App\node::find($this->rootId);
        $node = \App\node::find($request->input('nodeId'));
        $parent = \App\node::find($request->input('parentId'));

        if (empty($root)) {
            $message = "Root not found (" . $this->rootId . ")";
        } else if (empty($node)) {
            $message = "Node not found (" . $request->input('nodeId') . ")";
        } else if (empty($parent)) {
            $message = "Parent not found (" . $request->input('parentId') . ")";
        } else {
            $copy = $node->replicate();
            $node->addSibling($copy);
            $copy->save();

            if ($node->hasChildren()) {
                $array = Tree::duplicate($copy, $node->getChildren(), []);
                foreach($array as $key => $value) {
                   \App\node::insert($value);
                }
            }

            // $parent->addChild($copy);
            $success = true;
            $message = "Node copied (" . $node->id . ") new id (" . $copy->id . ") to parent (" . $parent->id . ")";
            $httpCode = 200;

        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'allCount' => $this->getCount(),
            'time' => microtime(true) - $start,
            'node' => !empty($node) ? $node : null
        ], $httpCode);
    }

    public function copyNodeChained(Request $request)
    {
        $start = microtime(true);
        $success = false;
        $httpCode = 500;

        $root = \App\node::where('id', $this->rootId)
            ->where('company_id', $this->companyId)
            ->first();
        $node = \App\node::where('id', $request->input('nodeId'))
            ->where('company_id', $this->companyId)
            ->first();
        $parent = \App\node::where('id', $request->input('parentId'))
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
        $root = \App\node::where('id', $this->rootId)
            ->where('company_id', $this->companyId)
            ->first();
        $parent = \App\node::where('id', $request->input('nodeId'))
            ->where('company_id', $this->companyId)
            ->first();

        if (empty($root)) {
            $message = "Unable to find root ...";
        } else if (empty($parent)) {
            $message = "Parent not found (" . $request->input('nodeId') . ")";
        } else {
            $node = new \App\node();
            $node->title = "New append node ...";
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

    public function getRandomLeaf(\App\node $node)
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
        $root = \App\node::find(1);

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
            $node = \App\node::find(rand(1, \App\node::count()));
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
                    $parent = \App\node::find($base->parent_id);
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
        $root = \App\node::find(1);

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
     * Duplicates the corresponding node by id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function duplicateById(Request $request)
    {
        $start = microtime(true);
        $success = false;
        $httpCode = 500;
        $node = \App\node::find($request->input('duplicateId'));
        $root = \App\node::find(1);

        if (empty($root)) {
            $message = "Unable to find root ...";
        } else if (is_null($node)) {
            $message = "Node not found (" . $request->input('duplicateId') . ") ...";
        } else {
            try {
                $baseClone = $this->duplicateNode($node);

                if (!is_null($baseClone)) {
                    $message = "Node (id: " . $node->id .", depth: " . $node->real_depth . ") copied to new node (id: " . $baseClone->id . ", depth: " . $baseClone->real_depth .") as sibling";
                    $success = true;
                    $httpCode = 200;
                } else {
                    $message = "Something went wrong when running duplicateNode ...";
                }
            } catch(\Exception $exception) {
                $message = $exception->getMessage();
                $httpCode = $exception->getCode();
            }
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'allCount' => $this->getCount(),
            'trees' => $this->getTrees(),
            'time' => microtime(true) - $start,
            'node' => !empty($baseClone) ? $baseClone : null
        ], $httpCode);
    }

    /**
     * Returns null if the tree duplication does not work, and returns an
     * instance of \App\node (the baseClone) otherwise
     * @param node $node
     * @return null|\App\node $baseClone
     * @throws \Exception
     */
    public function duplicateNode(\App\node $node)
    {
        $baseClone = null;

        if (!$node->isRoot()) {
            if (!$node->hasChildren()) {
                $baseClone = new \App\node();
                $baseClone->title = "clone " . $node->title;
                $node->addSibling($baseClone);
            } else {
                $baseClone = new \App\node();
                $baseClone->title = "clone " . $node->title;
                $node->addSibling($baseClone);
                $children = $node->getChildren();
                $this->duplicateTree($baseClone, $children);
            }
        } else {
            if (!$node->hasChildren()) {
                throw new \Exception("Node IS root, NO children", 501);
            } else {
                throw new \Exception("node is root, HAS children", 501);
            }
        }

        return $baseClone;
    }

    /**
     * Recursively takes the base's childrens and creates copy of them and
     * adds the returned copy as a children to the base
     * @param node $base
     * @param Collection $children
     */
    public function duplicateTree(\App\node $base, Collection $children)
    {
        foreach($children as $child) {
            $copy = new \App\node();
            $copy->title = $child->title;
            $returnedCopy = $base->addChild($copy, $child->position, true);

            if ($child->hasChildren()) {
                $this->duplicateTree($returnedCopy, $child->getChildren());
            }
        }
    }

    /**
     * Returns the first found (left-hand) leaf
     * @param node $node
     * @return node|mixed
     */
    public function getFirstLeaf(\App\node $node)
    {
        if (!$node->hasChildren()) {
            return $node;
        } else {
            $children = $node->getChildren();
            foreach($children as $child) {
                if (!$child->hasChildren()) {
                    return $child;
                } else {
                    return $this->getFirstLeaf($child);
                }
            }
        }
    }

    /**
     * Deletes a node and its children and descendants
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteById(Request $request)
    {
        $start = microtime(true);
        $success = false;
        $httpCode = 500;
        $root = \App\node::find(1);
        $node = \App\node::find($request->input('deleteId'));

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
     * Adds a new right-hand child to the root node
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addRootChild(Request $request)
    {
        $start = microtime(true);
        $success = false;
        $httpCode = 500;
        $root = \App\node::find(1);

        if (empty($root)) {
            $message = "root does not exist";
        } else {
            try {
                $newChild = new \App\node();
                $newChild->title = "new root child";

                if (!$root->hasChildren()) {
                    $root->addChild($newChild, 0);
                } else {
                    $lastChild = $root->getLastChild();
                    $position = $lastChild->position + 1;
                    $root->addChild($newChild, $position);
                }

                $message = "Root child added ...";
                $httpCode = 200;
                $success = true;
            } catch(\Exception $exception) {
                $message = $exception->getMessage();
                $httpCode = $exception->getCode();
            }
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'allCount' => $this->getCount($root),
            'time' => microtime(true) - $start,
            'node' => !empty($newChild) ? $newChild : null
        ], $httpCode);
    }

    /**
     * Deletes a node along with its children
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteNodeWithChildren(Request $request)
    {
        $start = microtime(true);
        $success = false;
        $httpCode = 500;
        $root = \App\node::find(1);

        if (empty($root)) {
            $message = "root does not exist";
        } else if (!$root->hasChildren()) {
            $message = "root has no children";
        } else {
            try {
                $deleteNode = $root->getChildren()[0];
                $deleteNode->deleteSubtree(true);
                $message = "Root child's subtree deleted ...";
                $httpCode = 200;
                $success = true;
            } catch(\Exception $exception) {
                $message = $exception->getMessage();
                $httpCode = $exception->getCode();
            }
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'allCount' => $this->getCount($root),
            'time' => microtime(true) - $start,
            'node' => !empty($deleteNode) ? $deleteNode : null
        ], $httpCode);
    }

    /**
     * Deletes the first found leaf on the left-hand side
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteLeaf(Request $request)
    {
        $start = microtime(true);
        $success = false;
        $httpCode = 500;
        $root = \App\node::find(1);

        if (empty($root)) {
            $message = "root does not exist";
        } else if (!$root->hasChildren()) {
            $message = "root has no children";
        } else {
            try {
                $leaf = $this->getFirstLeaf($root);
                $parent = $leaf->getParent();
                $parent->removeChild($leaf->position);
                $success = true;
                $httpCode = 200;
                $message = "Leaf deleted ...";
            } catch(\Exception $exception) {
                $message = $exception->getMessage();
            }
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'allCount' => $this->getCount($root),
            'time' => microtime(true) - $start,
            'node' => !empty($leaf) ? $leaf : null
        ], $httpCode);
    }

    /**
     * Adds a node as a new leaf on the first found existing leaf on the left-
     * hand side
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addLeaf(Request $request)
    {
        $start = microtime(true);
        $success = false;
        $httpCode = 500;

        $root = \App\node::find(1);

        if (empty($root)) {
            $message = "root does not exist";
        } else {
            try {
                $newLeaf = new \App\node();
                $newLeaf->title = "add-leaf node";

                if (!$root->hasChildren()) {
                    $root->addChild($newLeaf, 0);
                } else {
                    $leaf = $this->getFirstLeaf($root);
                    $leaf->addChild($newLeaf, 0);
                }

                $success = true;
                $httpCode = 200;
                $message = "Leaf added ...";
            } catch(\Exception $exception) {
                $message = $exception->getMessage();
            }
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'root' => $root,
            'allCount' => $this->getCount(),
            'time' => microtime(true) - $start,
            'node' => !empty($newLeaf) ? $newLeaf : null
        ], $httpCode);
    }

    /**
     * Returns the count of nodes in the tree, including the root
     * @param node $root
     * @return int
     */
    public function getCount()
    {
        return \App\node::count();
    }

    public function getTrees()
    {
        $trees = [];
        $roots = \App\node::getRoots();
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

        $root = \App\node::find(1);

        if (empty($root)) {
            $message = "root does not exist";
        } else {
            $tree = $root->getTree();
            $success = true;
            $message = "Tree data fetched ...";
            $httpCode = 200;
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
            'root' => $root,
            'tree' => $tree,
            'trees' => $this->getTrees(),
            'allCount' => $this->getCount($root),
            'time' => microtime(true) - $start
        ], $httpCode);
    }
}

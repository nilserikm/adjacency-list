<?php

namespace App\Http\Controllers;

use App\node;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class WelcomeController extends Controller
{
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
                $this->duplicateNode($node);
                $message = "Node duplicated ...";
                $success = true;
                $httpCode = 200;
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
            'treeDesc' => is_null($node) ? null : $node->getDescendantsTree()
        ], $httpCode);
    }

    /**
     * Returns true if the node was duplicated properly, false otherwise
     * @param node $node
     * @return boolean
     * @throws \Exception
     */
    public function duplicateNode(\App\node $node)
    {
        if (!$node->isRoot()) {
            if (!$node->hasChildren()) {
                $clone = new \App\node();
                $clone->title = "clone " . $node->title;
                $node->addSibling($clone);
            } else {
                $clone = new \App\node();
                $clone->title = "clone " . $node->title;
                $node->addSibling($clone);
                $children = $node->getChildren();
                $this->duplicateTree($clone, $children);
            }
        } else {
            if (!$node->hasChildren()) {
                throw new \Exception("Node IS root, NO children", 501);
            } else {
                throw new \Exception("node is root, HAS children", 501);
            }
        }

        return true;
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
            'allCount' => $this->getCount($root),
            'time' => microtime(true) - $start,
            'node' => !empty($newLeaf) ? $newLeaf : null
        ], $httpCode);
    }

    /**
     * Returns the count of nodes in the tree, including the root
     * @param node $root
     * @return int
     */
    public function getCount(\App\node $root)
    {
        return $root->countDescendants() + 1;
    }

    /**
     * Returns the node tree
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTree(Request $request)
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
            'allCount' => $this->getCount($root),
            'time' => microtime(true) - $start
        ], $httpCode);
    }
}

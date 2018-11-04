<?php

namespace App\Http\Controllers;

use App\node;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function deleteNodeWithChildren(Request $request)
    {
        $start = microtime(true);
        $success = true;
        $message = "delete node with children";
        $httpCode = 200;

        $root = \App\node::find(1);
        $time = microtime(true) - $start;

        return response()->json([
            'success' => $success,
            'message' => $message,
            'allCount' => $this->getCount($root),
            'time' => $time
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

        try {
            $root = \App\node::find(1);
            $leaf = $this->getFirstLeaf($root);
            $parent = $leaf->getParent();
            $parent->removeChild($leaf->position);

            $success = true;
            $httpCode = 200;
            $message = "Leaf deleted";
        } catch(\Exception $exception) {
            $success = false;
            $httpCode = 500;
            $message = $exception->getMessage();
        }

        $deleteLeafTime = microtime(true) - $start;

        return response()->json([
            'success' => $success,
            'message' => $message,
            'allCount' => $this->getCount($root),
            'deleteLeafTime' => $deleteLeafTime
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

        try {
            $root = \App\node::find(1);
            $newLeaf = new \App\node();
            $newLeaf->title = "add-leaf node";

            if (!$root->hasDescendants()) {
                $root->addChild($newLeaf, 0);
            } else {
                $leaf = $this->getFirstLeaf($root);
                $leaf->addChild($newLeaf, 0);
            }

            $success = true;
            $httpCode = 200;
            $message = "Leaf added";
        } catch(\Exception $exception) {
            $success = false;
            $httpCode = 500;
            $message = $exception->getMessage();
        }

        $addLeafTime = microtime(true) - $start;

        return response()->json([
            'root' => $root,
            'allCount' => $this->getCount($root),
            'addLeafTime' => $addLeafTime,
            'message' => $message,
            'success' => $success
        ], $httpCode);
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
        $root = \App\node::find(1);
        $start = microtime(true);
        $tree = $root->getTree();
        $fetchTreeTime = microtime(true) - $start;

        return response()->json([
            'root' => $root,
            'tree' => $tree,
            'fetchTreeTime' => $fetchTreeTime,
            'allCount' => $this->getCount($root)
        ]);
    }
}

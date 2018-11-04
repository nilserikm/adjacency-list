<?php

namespace App\Http\Controllers;

use App\node;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
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

                $message = "root child should be added now ...";
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
            'time' => microtime(true) - $start
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
//                $deletedNodeName = $deleteNode->title;
//                $deletedNodeId = $deleteNode->id;
                $deleteNode->deleteSubtree(true);
                $message = "subtree should be deleted now ...";
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
//            'deletedNodeName' => is_null($deletedNodeName) ? null : $deletedNodeName,
//            'deletedNodeId' => is_null($deletedNodeId) ? null : $deletedNodeId,
            'time' => microtime(true) - $start
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
                $message = "Leaf deleted";
            } catch(\Exception $exception) {
                $message = $exception->getMessage();
            }
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'allCount' => $this->getCount($root),
            'time' => microtime(true) - $start
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
                $message = "Leaf added";
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
        $start = microtime(true);
        $success = false;
        $httpCode = 500;

        $root = \App\node::find(1);

        if (empty($root)) {
            $message = "root does not exist";
        } else {
            $tree = $root->getTree();
            $success = true;
            $message = "tree fetched";
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

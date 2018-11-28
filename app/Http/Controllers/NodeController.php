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
            'allCount' => Node::getCount($this->companyId),
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
            'allCount' => Node::getCount($this->companyId),
            'time' => microtime(true) - $start,
            'node' => !empty($node) ? $node : null
        ], $httpCode);
    }

    /**
     * Returns a random node from the tree
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function randomNode(Request $request)
    {
        $start = microtime(true);
        $success = false;
        $httpCode = 500;
        $root = node::where('id', $this->rootId)
            ->where('company_id', $this->companyId)
            ->first();

        if (empty($root)) {
            $message = "Root not found (" . $this->rootId . ")";
        } else {
            $node = Node::getRandom($this->companyId);
            $node->path = Node::getPath($node, $this->companyId);

            $message = "Random node fetched (" . $node->id . ")";
            $success = true;
            $httpCode = 200;
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'allCount' => Node::getCount($this->companyId),
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
            'allCount' => Node::getCount($this->companyId),
            'deleteId' => $request->input('deleteId'),
            'time' => microtime(true) - $start,
            'node' => !empty($node) ? $node : null
        ], $httpCode);
    }

    /**
     * Returns all of the company's roots
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchRoots(Request $request)
    {
        $start = microtime(true);
        $success = false;

        try {
            $roots = node::where('company_id', $this->companyId)
                ->where('parent_id', null)
                ->get();
            $message = "Roots fetched";
            $success = true;
            $httpCode = 200;
        } catch(\Exception $exception) {
            $message = $exception->getMessage();
            $httpCode = $exception->getCode();
        }

        $time = microtime(true) - $start;

        return response()->json([
            'success' => $success,
            'message' => $message,
            'roots' => !empty($roots) ? $roots : null,
            'allCount' => Node::getCount($this->companyId),
            'time' => $time
        ], $httpCode);
    }

    /**
     * Returns all of the company's roots' descendants
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchDescendants(Request $request)
    {
        $start = microtime(true);
        $success = false;
        $httpCode = 500;

        $rootId = $request->input('rootId');
        $root = node::where('id', $rootId)
            ->where('company_id', $this->companyId)
            ->first();

        if (empty($root)) {
            $message = "Root (" . $rootId . ") not found";
        } else {
            try {
                $tree = $root->getDescendantsTree();
                $treeCount = ($root->countDescendants() + 1);
                $message = "Tree from root " . $rootId . " fetched";
                $success = true;
                $httpCode = 200;
            } catch(\Exception $exception) {
                $message = $exception->getMessage();
                $httpCode = $exception->getCode();
            }
        }

        $time = microtime(true) - $start;

        return response()->json([
            'success' => $success,
            'message' => $message,
            'tree' => !empty($tree) ? $tree : null,
            'treeCount' => !empty($treeCount) ? $treeCount : null,
            'allCount' => Node::getCount($this->companyId),
            'time' => $time
        ], $httpCode);
    }
}

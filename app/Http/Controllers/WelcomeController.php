<?php

namespace App\Http\Controllers;

use App\node;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function welcome(Request $request)
    {
        $start = microtime(true);
        $root = node::find(1);
        $tree = $root->getTree();

        $count = $root->countDescendants();
        $duration = microtime(true) - $start;

        $startAddDuration = microtime(true);
        $node = new \App\node();
        $node->title = "new node";
        $root->addChild($node);
        $addDuration = microtime(true) - $startAddDuration;
        $addCount = $root->countDescendants();

        $startDeleteDuration = microtime(true);
        $parent = $node->getParent();
        $parent->removeChild($node->position);
        $deleteDuration = microtime(true) - $startDeleteDuration;
        $deleteCount = $root->countDescendants();

        $startDeleteSubtreeDuration = microtime(true);
        $children = $root->getChildren();
        $children[0]->deleteSubtree();
        $deleteSubtreeDuration = microtime(true) - $startDeleteSubtreeDuration;
        $subtreeCount = $root->countDescendants();

        $afterCount = $root->countDescendants();

        return view('tree')->with([
            'tree' => $tree,
            'root' => $root,
            'duration' => $duration,
            'count' => $count,
            'addDuration' => $addDuration,
            'deleteDuration' => $deleteDuration,
            'deleteSubtreeDuration' => $deleteSubtreeDuration,
            'afterCount' => $afterCount,
            'addCount' => $addCount,
            'deleteCount' => $deleteCount,
            'subtreeCount' => $subtreeCount
        ]);
    }
}

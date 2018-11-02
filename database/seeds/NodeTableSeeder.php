<?php

use Illuminate\Database\Seeder;

class NodeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $start = microtime(true);

        // one project
        $root = new \App\node();
        $root->title = "root node";
        $root->save();
        $root->addChildren([
            // seven buildings
            new \App\node(['title' => 'level1 node']),
            new \App\node(['title' => 'level1 node']),
            new \App\node(['title' => 'level1 node']),
            new \App\node(['title' => 'level1 node']),
            new \App\node(['title' => 'level1 node']),
            new \App\node(['title' => 'level1 node']),
            new \App\node(['title' => 'level1 node'])
        ]);

        $level1Children = $root->getChildren();
        foreach ($level1Children as $level1Child) {
            $level2Node = \App\node::find($level1Child->id);
            $level2Node->addChildren([
                // ten phases
                new \App\node(['title' => 'level2 node']),
                new \App\node(['title' => 'level2 node']),
                new \App\node(['title' => 'level2 node']),
                new \App\node(['title' => 'level2 node']),
                new \App\node(['title' => 'level2 node']),
                new \App\node(['title' => 'level2 node']),
                new \App\node(['title' => 'level2 node']),
                new \App\node(['title' => 'level2 node']),
                new \App\node(['title' => 'level2 node']),
                new \App\node(['title' => 'level2 node']),
            ]);

            $level2Children = $level2Node->getChildren();
            foreach($level2Children as $level2Child) {
                $level3Node = \App\node::find($level2Child->id);
                $level3Node->addChildren([
                    // fifty apartments
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                    new \App\node(['title' => 'level3 node']),
                ]);

                $level3Children = $level3Node->getChildren();
                foreach($level3Children as $level3Child) {
                    $level4Node = \App\node::find($level3Child->id);
                    $level4Node->addChildren([
                        // thirty activities
                        new \App\node(['title' => 'level4 node']),
                        new \App\node(['title' => 'level4 node']),
                        new \App\node(['title' => 'level4 node']),
                        new \App\node(['title' => 'level4 node']),
                        new \App\node(['title' => 'level4 node']),
                        new \App\node(['title' => 'level4 node']),
                        new \App\node(['title' => 'level4 node']),
                        new \App\node(['title' => 'level4 node']),
                        new \App\node(['title' => 'level4 node']),
                        new \App\node(['title' => 'level4 node']),
                        new \App\node(['title' => 'level4 node']),
                        new \App\node(['title' => 'level4 node']),
                        new \App\node(['title' => 'level4 node']),
                        new \App\node(['title' => 'level4 node']),
                        new \App\node(['title' => 'level4 node']),
                        new \App\node(['title' => 'level4 node']),
                        new \App\node(['title' => 'level4 node']),
                        new \App\node(['title' => 'level4 node']),
                        new \App\node(['title' => 'level4 node']),
                        new \App\node(['title' => 'level4 node']),
                        new \App\node(['title' => 'level4 node']),
                        new \App\node(['title' => 'level4 node']),
                        new \App\node(['title' => 'level4 node']),
                        new \App\node(['title' => 'level4 node']),
                        new \App\node(['title' => 'level4 node']),
                        new \App\node(['title' => 'level4 node']),
                        new \App\node(['title' => 'level4 node']),
                        new \App\node(['title' => 'level4 node']),
                        new \App\node(['title' => 'level4 node']),
                        new \App\node(['title' => 'level4 node']),
                    ]);

//                    $level4Children = $level4Node->getChildren();
//                    foreach($level4Children as $level4Child) {
//                        $level5Node = \App\node::find($level4Child->id);
//                        $level5Node->addChildren([
//                            new \App\node(['title' => 'level5 node']),
//                            new \App\node(['title' => 'level5 node']),
//                            new \App\node(['title' => 'level5 node']),
//                            new \App\node(['title' => 'level5 node']),
//                            new \App\node(['title' => 'level5 node'])
//                        ]);
//
//                        $level5Children = $level5Node->getChildren();
//                        foreach($level5Children as $level5Child) {
//                            $level6Node = \App\node::find($level5Child->id);
//                            $level6Node->addChildren([
//                                new \App\node(['title' => 'level6 node']),
//                                new \App\node(['title' => 'level6 node']),
//                                new \App\node(['title' => 'level6 node']),
//                                new \App\node(['title' => 'level6 node']),
//                                new \App\node(['title' => 'level6 node'])
//                            ]);
//                        }
//                    }
                }
            }
        }

        $end = microtime(true) - $start;

        $insertStart = microtime(true);
        $node = \App\node::find(2);
        $node->addChild(new \App\node(['title' => 'test insert']));
        $insertEnd = microtime(true) - $insertStart;

        $moveStart = microtime(true);
        $sibling = $node->getFirstSibling();
        $node->moveTo(0, $sibling);
        $moveEnd = microtime(true) - $moveStart;

        $deleteStart = microtime(true);
        $node1 = \App\node::find(3);
        $parent = $node1->getParent();
        $parent->removeChild($node1->position);
        $deleteEnd = microtime(true) - $deleteStart;

        $this->command->info("bulk time: " . $end);
        $this->command->info("insert time: " . $insertEnd);
        $this->command->info("delete time: " . $deleteEnd);
        $this->command->info("move time: " . $moveEnd);
    }
}

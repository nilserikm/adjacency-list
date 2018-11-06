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

        $numBuildings = 5;
        $numPhases = 5;
        $numApartments = 20;
        $numActivities = 20;
        $numLeaves = 0;

        // LARGE VERSION
        // level 0 node (root)
        // "project"
        $root = new \App\node();
        $root->title = "root node";
        $root->save();

        // level 1 children
        // "buildings"
        $rootChildren = [];
        for ($i = 0; $i < $numBuildings; $i++) {
            $node = new \App\node(['title' => 'level1 node ' . $i]);
            array_push($rootChildren, $node);
        }
        $root->addChildren($rootChildren);

        foreach ($root->getChildren() as $child) {
            // level 2 children
            // "phases"
            $level1Children = [];
            for ($i = 0; $i < $numPhases; $i++) {
                $node = new \App\node(['title' => 'level2 node ' . $i]);
                array_push($level1Children, $node);
            }
            $child->addChildren($level1Children);

            foreach($level1Children as $level1Child) {
                // level 3 children
                // "apartments"
                $level2Children = [];
                for ($i = 0; $i < $numApartments; $i++) {
                    $node = new \App\node(['title' => 'level3 node ' . $i]);
                    array_push($level2Children, $node);
                }
                $level1Child->addChildren($level2Children);

                foreach($level2Children as $level2Child) {
                    // level 4 children
                    // "activities"
                    $level3Children = [];
                    for ($i = 0; $i < $numActivities; $i++) {
                        $node = new \App\node(['title' => 'level4 node ' . $i]);
                        array_push($level3Children, $node);
                    }
                    $level2Child->addChildren($level3Children);

                    foreach($level3Children as $level3Child) {
                        // level 5 children
                        // "leaves"
                        $level4Children = [];
                        for ($i = 0; $i < $numLeaves; $i++) {
                            $node = new \App\node(['title' => 'level5 node ' . $i]);
                            array_push($level4Children, $node);
                        }
                        $level3Child->addChildren($level4Children);
                    }
                }
            }
        }

        $end = microtime(true);

        $this->command->info("migration time: " . ($end - $start));
    }
}

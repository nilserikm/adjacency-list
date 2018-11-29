<?php

use Illuminate\Database\Seeder;

class K1Nodes1Tree1Company extends Seeder
{

    public $runStats = false;
    public $runDuplication = false;
    public $numIterations = 1;
    public $companyId = 1;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $start = microtime(true);

        // the number of nodes in each level for data-resemblance to a "real"
        // Markhus-project
//        $numBuildings = 3;
//        $numPhases = 7;
//        $numApartments = 20;
//        $numActivities = 25;
//        $numLastLevel = 0;

        // the number of nodes in each level for a "basic" setup
//        $numBuildings = 1;
//        $numPhases = 2;
//        $numApartments = 5;
//        $numActivities = 10;
//        $numLastLevel = 0;

        $buildingNames = ["H6-H10", "H2-H5", "R3", "R11", "BZ-VEST"];

        $companyId = 1;
        $cloneNodeId = null;
        $cloneParentId = null;

        for ($iteration = 0; $iteration < $this->numIterations; $iteration++) {
            $this->command->info('starting iteration: ' . $iteration);
            $iStart = microtime(true);

            $numBuildings = 1;
            $numBuildings = 5;
            for ($i = 0; $i < $numBuildings; $i++) {
                $building = new \App\node();
                $building->title = $buildingNames[$i];
                $building->company_id = $companyId;
                $building->estimate = 0;
                $building->save();
                $cloneParentId = $building->id;

                $numPhases = 2;
                $numPhases = rand(3, 7);
                $phases = [];
                for ($j = 0; $j < $numPhases; $j++) {
                    $node = new \App\node([
                        'title' => 'phase ' . $j,
                        'company_id' => $companyId,
                        'estimate' => 0
                    ]);
                    array_push($phases, $node);
                }
                $building->addChildren($phases);

                foreach ($building->getChildren() as $phase) {
                    $this->command->info($phase->title);
                    $cloneNodeId = $phase->id;

                    $numApartments = 2;
                    $numApartments = rand(10, 20);
                    $apartments = [];
                    for ($k = 0; $k < $numApartments; $k++) {
                        $node = new \App\Node([
                            'title' => 'apartment ' . $k,
                            'company_id' => $companyId,
                            'estimate' => 0
                        ]);
                        array_push($apartments, $node);
                    }
                    $phase->addChildren($apartments);

                    foreach ($phase->getChildren() as $apartment) {

                        $numActivities = 2;
                        $numActivities = rand(15, 25);
                        $activities = [];
                        for ($n = 0; $n < $numActivities; $n++) {
                            $node = new \App\Node([
                                'title' => 'activity ' . $n,
                                'company_id' => $companyId,
                                'estimate' => rand(1, 100)
                            ]);
                            array_push($activities, $node);
                        }
                        $apartment->addChildren($activities);

                        foreach ($apartment->getChildren() as $activity) {

                            $numLastLevel = 0;
                            $lastLevels = [];
                            for ($m = 0; $m < $numLastLevel; $m++) {
                                $node = new \App\Node([
                                    'title' => 'last-level ' . $m,
                                    'company_id' => $companyId,
                                    'estimate' => 0
                                ]);
                                array_push($lastLevels, $node);
                            }
                            $activity->addChildren($lastLevels);
                        }
                    }
                }
            }

            if ($this->runStats) {
                $iTime = microtime(true) - $iStart;
                $this->command->info("seed time: " . $iTime);

                $cloneNode = \App\node::find($cloneNodeId);
                $copy = $cloneNode->replicate();
                $copy->save();

                if ($this->runDuplication) {
                    $cloneStart = microtime(true);
                    $duplicate = function($base, $children) use (&$duplicate) {
                        foreach($children as $child) {
                            $clone = $child->replicate();
                            $base->addChild($clone);

                            if ($child->hasChildren()) {
                                $duplicate($clone, $child->getChildren());
                            }
                        }

                        return $base;
                    };

                    $base = $duplicate($copy, $cloneNode->getChildren());
                    $parent = \App\node::where('id', $cloneParentId)
                        ->where('company_id', $companyId)
                        ->first();
                    $parent->addChild($base);
                    $cloneTime = microtime(true) - $cloneStart;
                    $this->command->info('clone time: ' . $cloneTime);
                }

                $rStart = microtime(true);
                $tempRoot = \App\node::find($cloneParentId);
                $tempTree = $tempRoot->getDescendantsTree();
                $readTime = microtime(true) - $rStart;
                $this->command->info('read tree size: ' . ($tempRoot->countDescendants() + 1));
                $this->command->info('read time: ' . $readTime);

                $cloneNodeParent = \App\node::find($cloneParentId);

                $clonedNum = ($cloneNode->countDescendants() + 1);
                $treeNum = ($cloneNodeParent->countDescendants() + 1);
                $tableNum = \App\Node::count();

                $this->command->info('number of added nodes: ' . $clonedNum);
                $this->command->info('nodes in tree: ' . $treeNum);
                $this->command->info('nodes in table: ' . $tableNum);
                \Illuminate\Support\Facades\DB::table('stats')->insert([
                    'clone_time' => !empty($cloneTime) ? $cloneTime : null,
                    'cloned_num' => !empty($clonedNum) ? $clonedNum : null,
                    'tree_num' => $treeNum,
                    'table_num' => $tableNum,
                    'read_time' => $readTime
                ]);
                $this->command->info("-----------------------------------------");
                $this->command->info('');
                $this->command->info('');
            }

            $companyId++;
        }

        $end = microtime(true);

        $this->command->info("migration time: " . ($end - $start));
    }
}

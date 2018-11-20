<?php

use Illuminate\Database\Seeder;

class K1Nodes1Tree1Company extends Seeder
{

    public $runStats = true;
    public $runDuplication = false;
    public $numIterations = 10;
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
//        $numBuildings = 1;
//        $numPhases = 5;
//        $numApartments = 7;
//        $numActivities = 20;
//        $numLastLevel = 25;

        // the number of nodes in each level for a "basic" setup
        $numBuildings = 1;
        $numPhases = 2;
        $numApartments = 5;
        $numActivities = 10;
        $numLastLevel = 0;

        $companyId = 1;
        $cloneNodeId = null;
        $cloneParentId = null;

        for ($iteration = 0; $iteration < $this->numIterations; $iteration++) {
            $this->command->info('starting iteration: ' . $iteration);
            $iStart = microtime(true);

            for ($i = 0; $i < $numBuildings; $i++) {
                $building = new \App\node();
                $building->title = 'building ' . $i;
                $building->company_id = $companyId;
                $building->save();
                $cloneParentId = $building->id;

                $phases = [];
                for ($j = 0; $j < $numPhases; $j++) {
                    $node = new \App\node([
                        'title' => 'phase ' . $j,
                        'company_id' => $companyId
                    ]);
                    array_push($phases, $node);
                }
                $building->addChildren($phases);

                foreach ($building->getChildren() as $phase) {
                    $cloneNodeId = $phase->id;
                    $apartments = [];
                    for ($k = 0; $k < $numApartments; $k++) {
                        $node = new \App\Node([
                            'title' => 'apartment ' . $k,
                            'company_id' => $companyId
                        ]);
                        array_push($apartments, $node);
                    }
                    $phase->addChildren($apartments);

                    foreach ($phase->getChildren() as $apartment) {
                        $activities = [];
                        for ($n = 0; $n < $numActivities; $n++) {
                            $node = new \App\Node([
                                'title' => 'activity ' . $n,
                                'company_id' => $companyId
                            ]);
                            array_push($activities, $node);
                        }
                        $apartment->addChildren($activities);

                        foreach ($apartment->getChildren() as $activity) {
                            $lastLevels = [];
                            for ($m = 0; $m < $numLastLevel; $m++) {
                                $node = new \App\Node([
                                    'title' => 'last-level ' . $m,
                                    'company_id' => $companyId
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

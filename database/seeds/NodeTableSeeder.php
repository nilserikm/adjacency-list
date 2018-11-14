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

        // seed time ~20s
        // node count ~3.5k
        // number of buildings matches number of entries in buildingNames
        $buildingNames = ["H6-H10"];
        $numPhases = 7;
        $numApartments = 20;
        $numActivities = 25;
        $numUnnamedFirst = 0;
        $numUnnamedSecond = 0;

        foreach ($buildingNames as $buildingName) {
            // "building"
            $root = new \App\node();
            $root->title = $buildingName;
            $root->save();

            // "phases"
            $rootChildren = [];
            $phases = ["Rigg og drift", "Tett hus", "Utvendig", "Innvendig", "Innredning", "Ikke-navngitt fase", "Elementproduksjon"];
            for ($i = 0; $i < $numPhases; $i++) {
                $node = new \App\node(['title' => $phases[$i]]);
                array_push($rootChildren, $node);
            }
            $root->addChildren($rootChildren);

            foreach ($root->getChildren() as $child) {
                // "apartments"
                $level1Children = [];
                for ($i = 0; $i < $numApartments; $i++) {
                    if ($i < 10) {
                        $num = "0" . $i;
                    } else {
                        $num = $i;
                    }

                    $node = new \App\node(['title' => 'H01' . $num]);
                    array_push($level1Children, $node);
                }
                $child->addChildren($level1Children);

                foreach($level1Children as $level1Child) {
                    // "activities"
                    $level2Children = [];
                    $activities = [
                        "Maling", "Snekring", "Listing", "Dobling", "Tripling",
                        "Sparkling", "Trekking", "Elektriker", "Tapetsering", "Rydding",
                        "Kobling", "Rørlegger", "Tørking", "Vasking", "Gulvlegging",
                        "Ventilasjon", "Gipsing", "Banking", "Tømring", "Møblering",
                        "Membran", "Dusj og toalett", "Kjøkken", "Sikring balkong", "Lamper"
                    ];
                    for ($i = 0; $i < $numActivities; $i++) {
                        if ($i < count($activities)) {
                            $activity = $activities[$i];
                        } else {
                            $activities = "Ikke-navngitt aktivitet";
                        }

                        $node = new \App\node(['title' => $activity]);
                        array_push($level2Children, $node);
                    }
                    $level1Child->addChildren($level2Children);

                    foreach($level2Children as $level2Child) {
                        // level 4 children
                        // "activities"
                        $level3Children = [];
                        for ($i = 0; $i < $numUnnamedFirst; $i++) {
                            $node = new \App\node(['title' => 'level4 node ' . $i]);
                            array_push($level3Children, $node);
                        }
                        $level2Child->addChildren($level3Children);

                        foreach($level3Children as $level3Child) {
                            // level 5 children
                            // "leaves"
                            $level4Children = [];
                            for ($i = 0; $i < $numUnnamedSecond; $i++) {
                                $node = new \App\node(['title' => 'level5 node ' . $i]);
                                array_push($level4Children, $node);
                            }
                            $level3Child->addChildren($level4Children);
                        }
                    }
                }
            }

        }

        $end = microtime(true);

        $this->command->info("migration time: " . ($end - $start));
    }
}

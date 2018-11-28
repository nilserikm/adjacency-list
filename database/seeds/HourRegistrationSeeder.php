<?php

use Illuminate\Database\Seeder;

class HourRegistrationSeeder extends Seeder
{
    public $companyId = 1;
    public $break = 30;

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $nodes = \App\node::where('company_id', $this->companyId)
            ->where('real_depth', 3)
            ->get();

        foreach ($nodes as $node) {
            $numHourReg = rand(3, 7);
            for ($i = 0; $i < $numHourReg; $i++) {
                $hourRegistration = new \App\HourRegistration([
                    'company_id' => $this->companyId,
                    'efficiency' => (ceil(rand(10, 100))/100),
                    'to' => new DateTime(),
                    'from' => new DateTime(),
                    'break' => $this->break,
                    'duration' => rand(180, 720),
                    'comment' => $faker->sentence()
                ]);
                $hourRegistration->save();
                $hourRegistration->nodes()->attach($node->id);
            }
        }
    }
}

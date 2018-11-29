<?php

use Illuminate\Database\Seeder;

class HourRegistrationSeeder extends Seeder
{
    public $companyId = 1;
    public $break = 30;

    public function duration(DateTime $date1, DateTime $date2, int $break)
    {
        $diff = $date1->diff($date2);
        return (((($diff->days * 24) + $diff->h) * 60) + $diff->i) - $break;
    }

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

        $start = new DateTime('2018-11-28T07:30:00');
        $end = new DateTime('2018-11-28T16:00:00');

        foreach ($nodes as $node) {
            $numHourReg = rand(2, 5);
            for ($i = 0; $i < $numHourReg; $i++) {
                $hourRegistration = new \App\HourRegistration([
                    'company_id' => $this->companyId,
                    'start' => $start,
                    'end' => $end,
                    'duration' => $this->duration($start, $end, $this->break),
                    'efficiency' => ((rand(1, 10) * 10) / 100),
                    'break' => $this->break,
                    'comment' => $faker->sentence()
                ]);
                $hourRegistration->save();
                $hourRegistration->nodes()->attach($node->id);
            }
        }
    }
}

<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(NodeTableSeeder::class);
        $this->call(K1Nodes1Tree1Company::class);
        $this->call(HourRegistrationSeeder::class);
    }
}

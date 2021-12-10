<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    private static $numSeedersRan = 0;

    public function run()
    {
        $this->call(UserSeeder::class);

        if (!static::$numSeedersRan) {
            $this->command->warn("Nothing to seed.");
        }
    }

    public function call($class, $silent = false)
    {
        // check if the seeder already run
        $seeder = DB::collection('seeders')->where('class', $class)->first();
        if ($seeder) {
            return;
        }

        // run the seeder
        parent::call($class, $silent);

        // add the seeder to seeded list
        DB::collection('seeders')->insert([
            'class' => $class,
            'ran_at' => date('Y-m-d H:i:s'),
        ]);

        static::$numSeedersRan++;
    }
}

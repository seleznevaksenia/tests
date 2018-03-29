<?php

use Illuminate\Database\Seeder;

class ResponseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $now = \Carbon\Carbon::now();
        for ($i = 0; $i <= 1000; $i++) {
            \Illuminate\Support\Facades\DB::table('responses')->insert([
                'site_id' => 1, 'time' => $faker->numberBetween(100,500),'created_at' => $now->subMinutes(5*(1+$i))->format("Y-m-d H:i:s")]);

        }
        $now = \Carbon\Carbon::now();
        for ($i = 0; $i <= 1000; $i++) {
            \Illuminate\Support\Facades\DB::table('responses')->insert([
                'site_id' => 2, 'time' => $faker->numberBetween(100,500),'created_at' => $now->subMinutes(5*(1+$i))->format("Y-m-d H:i:s")]);

        }
        $now = \Carbon\Carbon::now();
        for ($i = 0; $i <= 1000; $i++) {
            \Illuminate\Support\Facades\DB::table('responses')->insert([
                'site_id' => 3, 'time' => $faker->numberBetween(100,500),'created_at' => $now->subMinutes(5*(1+$i))->format("Y-m-d H:i:s")]);

        }
    }
}

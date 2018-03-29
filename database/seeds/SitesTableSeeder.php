<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SitesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sites')->insert([
            ['name' => 'HubCulture', 'link' => 'https://hubculture.com/'],
            ['name' => 'Admin HubCulture', 'link' => 'https://hubculture.com/admin/'],
            ['name' => 'Ven.vc', 'link' => 'https://ven.vc/'],
            ['name' => 'HubID API', 'link' => 'http://www.id.hubculture.com/'],
        ]);

    }
}

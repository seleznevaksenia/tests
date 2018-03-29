<?php

namespace App\Console\Commands;

use App\Incident;
use App\Site;
use App\Test;
use App\TestCategory;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GetTestResult extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getTestResult';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $test = Test::orderBy('id', 'desc')->first();
        if ($test) {
            $id = $test->id;
            $site_id = Site::where('link','http://www.id.hubculture.com/')->first()->id;
            $results = TestCategory::where('test_id', $id)
                ->orderBy('id')
                ->whereNotIn('subcategory', ['after', 'before', 'helpers'])
                ->get();
            foreach ($results as  $result){
                if($result->status != 'pass'){
                    $category = str_replace('Test',"",$result->category);
                    Incident::created([
                        'incident' => "Unit Test failed in ".$category."category",
                        'site_id' => $site_id
                    ]);
                }
            }
        }

    }
}

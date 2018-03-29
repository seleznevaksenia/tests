<?php

namespace App\Console\Commands;

use App\Incident;
use App\Response;
use App\Site;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class PingSites extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ping';

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
        $client = new Client();
        $urls = Site::all();
        $statistic = null;
        $time = null;
        foreach ($urls as $url){
            $client->request('GET', $url->link, [
                'on_stats' => function (\GuzzleHttp\TransferStats $stats) use (&$statistic,&$code){
                    $statistic = $stats->getHandlerStats();
                    // You must check if a response was received before using the
                    // response object.
                    if ($stats->hasResponse()) {

                        $code =  $stats->getResponse()->getStatusCode();
                    } else {
                        // Error data is handler specific. You will need to know what
                        // type of error data your handler uses before using this
                        // value.
                        Log::info("Error during response");
                    }
                }
            ]);

            if($code == 200){
                $time = round($statistic['total_time']*1000);
            }
            if($statistic['total_time'] > 2){
                Incident::created([
                    'incident' => 'Response Timeout',
                    'site_id' => $url->id,
                    'status' => 0
                ]);
            }
            Response::create(['site_id' => $url->id,
                'time' => $time]);
        }
    }
}

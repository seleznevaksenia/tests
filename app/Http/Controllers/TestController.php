<?php

namespace App\Http\Controllers;

use App\Incident;
use App\Response;
use App\Site;
use App\Test;
use App\TestCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * @param Request $request
     * @return json
     */
    public function result()
    {
        $results = [];
        $dates = [];
        $end = Carbon::now()->subDays(15);

        $test = Test::orderBy('id', 'desc')->first();
        $incidents = Incident::orderBy('id', 'desc')
            ->with('site')
            ->where('created_at','>=',$end->format("Y-m-d H:i:s"))
            ->get(['incident','site_id','status','created_at']);


        for($date = Carbon::now(); $date->gte($end); $date->subDay()) {
            $dates[$date->format('M d, Y')] = [];
            $incident_array = [];
            foreach ($incidents as $incident){
                if(date("Y-m-d", strtotime($incident->created_at)) === $date->format("Y-m-d")){
                    array_push($incident_array,$incident);
                    $dates[$date->format('M d, Y')] = $incident;
                }
            }
            $dates[$date->format('M d, Y')] = $incident_array;
        }

        if ($test) {
            $now = Carbon::now();
            $test_date = Carbon::createFromFormat("Y-m-d H:i:s",$test->created_at);
            $test_refreshed = $test_date->diffInMinutes($now, false);
            $id = $test->id;
            $results = TestCategory::where('test_id', $id)
                ->orderBy('id')
                ->whereNotIn('subcategory', ['after', 'before', 'helpers'])
                ->get();
        }

        return response([
            'dates' => $dates,
            'results' => $results,
            'incidents' => $incidents,
            'test_refreshed' => $test_refreshed
        ], 200);
    }

    public function charts($period)
    {

        $now = Carbon::now();
            if ($period == 'day') {
                $end = $now->subDay()->format("Y-m-d H:i:s");
            } elseif ($period = 'week') {
                $end = $now->subDays(7)->format("Y-m-d H:i:s");
            } elseif ($period = 'month') {
                $end = $now->subMonth()->format("Y-m-d H:i:s");
            }
        $hub = [];
        $hub_dates = [];
        $hub_id = Site::where('link', 'https://hubculture.com/')->first()->id;
        $hub_query = Response::where('site_id', $hub_id)
            ->where('created_at', '>=', $end)
            ->get(['time', 'created_at']);
        foreach ($hub_query as $value) {
            $hub[] = $value->time;
            $hub_dates[] = $value->{$period};
        }

        $hubadmin = [];
        $hubadmin_dates = [];
        $hubadmin_id = Site::where('link', 'https://hubculture.com/admin/')->first()->id;
        $hubadmin_query = Response::where('site_id', $hubadmin_id)
            ->where('created_at', '>=', $end)
            ->get(['time', 'created_at']);
        foreach ($hubadmin_query as $value) {
            $hubadmin[] = $value->time;
            $hubadmin_dates[] = $value->{$period};
        }

        $venvc = [];
        $venvc_dates = [];
        $venvc_id = Site::where('link', 'https://ven.vc/')->first()->id;
        $venvc_query = Response::where('site_id', $venvc_id)
            ->where('created_at', '>=', $end)
            ->get(['time', 'created_at']);

        foreach ($venvc_query as $value) {
            $venvc[] = $value->time;
            $venvc_dates[] = $value->{$period};
        }


        return response([
            'hub' => $hub,
            'hubadmin' => $hubadmin,
            'venvc' => $venvc,
            'hub_dates' => $hub_dates,
            'hubadmin_dates' => $hubadmin_dates,
            'venvc_dates' => $venvc_dates
        ], 200);

    }

}

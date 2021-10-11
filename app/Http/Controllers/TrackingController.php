<?php

namespace App\Http\Controllers;

use App\Models\Tracking;

use Illuminate\Http\Request;

class TrackingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $records = Tracking::with('booking')
                            ->orderBy('tracking_time', 'DESC')
                            ->get()
                            ->all();

        $trackings = [];
        foreach ($records as $r) {
            $tracking = new \stdClass();
            $tracking->id = $r->id;
            $tracking->equ_id = $r->booking->equipment->id;
            $tracking->equ_code = $r->booking->equipment->equ_code;
            $tracking->equ_name = $r->booking->equipment->equ_name;
            $tracking->equ_cat = $r->booking->equipment->category->cat_name;
            $tracking->booking_id = $r->booking_id;
            $tracking->staff = $r->user->first_name . ' ' . $r->user->last_name;
            $tracking->status = $r->status;
            $tracking->status_name = getStatusName('tracking', $r->status);
            $tracking->status_class_name = getStatusClassName('tracking', $r->status);
            $tracking->tracking_time = $r->tracking_time;
            
            array_push($trackings, $tracking);
        }
        // dd($trackings);
        $nl_trackings_class = 'active';

        return view('pages.tracking', compact('trackings', 'nl_trackings_class'));
    }
}

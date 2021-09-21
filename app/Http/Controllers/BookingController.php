<?php

namespace App\Http\Controllers;
use App\Models\Equipment;
use App\Models\Booking;
use App\Models\Tracking;

use Illuminate\Http\Request;
use Auth;

class BookingController extends Controller
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
        $bookings = getBookingsByUser(Auth::user()->id);
        
        $nl_bookings_class = 'active';

        return view('pages.booking', compact('bookings', 'nl_bookings_class'));
    }

    public function indexForManage(Request $request)
    {
        $bookings = getAllBookings();
                
        $nl_bookings_class = 'active';

        return view('pages.manage-booking', compact('bookings', 'nl_bookings_class'));
    }

    public function requestBooking(Request $request)
    {
        $id = $request->get('id');
        $booking_start = $request->get('booking_start');
        $booking_end = $request->get('booking_end');

        // $equipment = getEquipmentById($id);
        $equipment = Equipment::find($id);

        if (empty($equipment)) {
            return response()->json([
                'success' => false,
                'message' => 'Error: Equipment not exists.'
            ]);
        }

        // Set Equipment Status
        $equipment->equ_status = '1'; //booking
        $equipment->save();
        $equipment->getStatusName();
        
        // Create Booking
        $booking = new Booking();
        $booking->equ_id = $id;
        $booking->booking_user = Auth::user()->id;
        $booking->booking_date = date('Y-m-d');
        $booking->status = '0'; //booking
        $booking->booking_start = $booking_start;
        $booking->booking_end = $booking_end;
        $booking->save();
        
        if (empty($booking->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, error occurs while booking.'
            ]);
        }

        // Track Booking
        $tracking = new Tracking();
        $tracking->booking_id = $booking->id;
        $tracking->tracking_time = now();
        $tracking->staff = Auth::user()->id;
        $tracking->status = '0'; //booking
        $tracking->save();

        if (empty($tracking->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, error occurs while tracking.'
            ]);
        } else {
            return response()->json([
                'success' => true,
                'equipment' => $equipment
            ]);
        }
    }

    public function removeBooking(Request $request)
    {
        $bId = $request->get('id');
        $booking = getBookingById($bId);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'Error: Booking(ID: ' . $bId . ') not exists.'
            ]);
        }

        $equ_code = $booking->equipment->equ_code;
        $user_name = $booking->user->first_name . ' ' . $booking->user->last_name;
        $equipment = Equipment::find($booking->equipment->id);
        if (empty($equipment)) {
            return response()->json([
                'success' => false,
                'message' => 'Error: Equipment(CODE: ' . $equ_code . ') not exists.'
            ]);
        }

        // Track Booking
        $tracking = new Tracking();
        $tracking->booking_id = $booking->id;
        $tracking->tracking_time = now();
        $tracking->staff = Auth::user()->id;
        $tracking->status = '4'; //deleted
        $tracking->save();

        if (empty($tracking->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, error occurs while tracking.'
            ]);
        }

        // Change Equipment Status
        $equipment->equ_status = '0'; //available
        $equipment->save();

        // Delete Booking
        $booking->delete();

        \Session::flash('success', 'Booking(ID: ' . $bId . ', Equipment: ' 
                                    . $equ_code . ', Student: ' 
                                    . $user_name . ') was deleted successfully.');

        return response()->json([
            'success' => true
        ]);
    }

    public function getBooking(Request $request)
    {
        $id = $request->get('id');

        $booking = getBookingById($id);

        return response()->json([
            'success' => !empty($booking),
            'booking' => $booking
        ]);
    }

    public function updateBookingStatus(Request $request)
    {
        $id = $request->get('id');
        $status = $request->get('status');

        $booking = Booking::find($id);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'Error: Booking(ID: ' . $bId . ') not exists.'
            ]);
        }

        // Track Booking
        $tracking = new Tracking();
        $tracking->booking_id = $booking->id;
        $tracking->tracking_time = now();
        $tracking->staff = Auth::user()->id;
        $tracking->status = $status;
        $tracking->save();

        if (empty($tracking->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, error occurs while tracking.'
            ]);
        }

        // Change Booking Status
        $booking->status = $status;
        $booking->staff = Auth::user()->id;
        $booking->save();

        $booking = getBookingById($id);

        // Change Equipment Status
        $equipment = Equipment::find($booking->equ_id);

        if ($booking->status == '0') {
            $equipment->equ_status = '1'; //in booking
        } else if ($booking->status == '1') {
            $equipment->equ_status = '2'; //pickup(booked/approved)
        } else if ($booking->status == '2') {
            $equipment->equ_status = '0'; //available(rejected)
        }
        $equipment->save();        

        return response()->json([
            'success' => true,
            'booking' => $booking
        ]);
    }

    public function cancelBooking(Request $request)
    {
        $id = $request->get('id');
        
        $booking = Booking::find($id);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'Error: Booking(ID: ' . $bId . ') not exists.'
            ]);
        }

        // Track Booking
        $tracking = new Tracking();
        $tracking->booking_id = $booking->id;
        $tracking->tracking_time = now();
        $tracking->staff = Auth::user()->id;
        $tracking->status = '3';
        $tracking->save();

        if (empty($tracking->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, error occurs while tracking.'
            ]);
        }

        // Change Booking Status
        $booking->status = '3';
        $booking->save();

        $booking = getBookingById($id);

        // Change Equipment Status
        $equipment = Equipment::find($booking->equ_id);
        $equipment->equ_status = '0'; //available(cancelled)
        $equipment->save();

        \Session::flash('success', 'Booking(ID: B-' . $booking->id . ') was cancelled successfully.');

        return response()->json([
            'success' => true
        ]);
    }

    public function returnBooking(Request $request)
    {
        $id = $request->get('id');
        
        $booking = Booking::find($id);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'Error: Booking(ID: ' . $bId . ') not exists.'
            ]);
        }

        // Track Booking
        $tracking = new Tracking();
        $tracking->booking_id = $booking->id;
        $tracking->tracking_time = now();
        $tracking->staff = Auth::user()->id;
        $tracking->status = '5';
        $tracking->save();

        if (empty($tracking->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, error occurs while tracking.'
            ]);
        }

        // Change Booking Status
        $booking->status = '4';
        $booking->save();

        $booking = getBookingById($id);

        // Change Equipment Status
        $equipment = Equipment::find($booking->equ_id);
        $equipment->equ_status = '0'; //available(returned)
        $equipment->save();

        \Session::flash('success', 'Equipment(CODE: ' . $equipment->equ_code . 
                                    ', Name: ' . $equipment->equ_name . ') was returned successfully.');

        return response()->json([
            'success' => true
        ]);
    }

    public function updateBookingPeriod(Request $request)
    {
        $id = $request->get('id');
        $booking_start = $request->get('booking_start');
        $booking_end = $request->get('booking_end');

        $booking = Booking::find($id);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'Error: Booking(ID: ' . $bId . ') not exists.'
            ]);
        }

        // Track Booking
        $tracking = new Tracking();
        $tracking->booking_id = $booking->id;
        $tracking->tracking_time = now();
        $tracking->staff = Auth::user()->id;
        $tracking->status = '6'; //changed booking info
        $tracking->save();

        if (empty($tracking->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, error occurs while tracking.'
            ]);
        }

        // Change Booking Period
        $booking->booking_start = $booking_start;
        $booking->booking_end = $booking_end;
        $booking->save();

        \Session::flash('success', 'Booking(ID: B-' . $booking->id . ') period was changed successfully.');

        return response()->json([
            'success' => true
        ]);
    }
}

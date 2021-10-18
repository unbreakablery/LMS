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
        $booking_qnt = $request->get('booking_qnt');
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

        // Check booking quantity
        if ($booking_qnt > $equipment->equ_current_qnt) {
            return response()->json([
                'success' => false,
                'message' => 'Error: Booking quantity should be less than current quantity.'
            ]);
        }

        // Set Equipment current quantity and status
        $equipment->equ_current_qnt -= $booking_qnt;
        $equipment->equ_status = ($equipment->equ_current_qnt == 0) ? '0' : '1'; //unbookable or bookable
        $equipment->save();
        $equipment->getStatusName();
        
        // Create Booking
        $booking = new Booking();
        $booking->equ_id = $id;
        $booking->booking_user = Auth::user()->id;
        $booking->booking_date = date('Y-m-d');
        $booking->status = '0'; //booking
        $booking->booking_qnt = $booking_qnt;
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

        // Change Equipment current quantity and status
        if ($booking->status == '0') {
            $equipment->equ_current_qnt += $booking->booking_qnt;
            $equipment->equ_status = '1'; //bookable
        }
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
        $equipment = Equipment::find($booking->equ_id);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'Error: Booking(ID: ' . $id . ') not exists.'
            ]);
        }

        if ($booking->status == $status) {
            return response()->json([
                'success' => false,
                'message' => 'Error: You didn\'t change the booking status.'
            ]);
        }

        if ($status == '0' && $booking->booking_qnt > $equipment->equ_current_qnt) {
            return response()->json([
                'success' => false,
                'message' => 'Error: Can\'t change booking status because of booking and current quantity.'
            ]);
        }

        if ($booking->status != '0' && $booking->status != '1' && 
            ($status == '0' || $status == '1') && 
            $booking->booking_qnt > $equipment->equ_current_qnt) {
            return response()->json([
                'success' => false,
                'message' => 'Error: Can\'t change booking status because of booking quantity is greater than current quantity in storage.'
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
        $old_booking_status = $booking->status;
        $booking->status = $status;
        $booking->staff = Auth::user()->id;
        $booking->save();

        $booking = getBookingById($id);

        // Change Equipment current quantity and status
        if ($old_booking_status == '0') {
            if ($status != '1') { //from in booking to rejected, cancelled, and returned
                $equipment->equ_current_qnt += $booking->booking_qnt;
            }
        } else if ($old_booking_status == '1') {
            if ($status != '0') { //from approved to rejected, cancelled, and returned
                $equipment->equ_current_qnt += $booking->booking_qnt;
            }
        } else {
            if ($status == '0' || $status == '1') { //from rejected, cancelled, and returned to in booking or approved
                $equipment->equ_current_qnt -= $booking->booking_qnt;
            }
        }
        
        if ($equipment->equ_current_qnt == 0) {
            $equipment->equ_status = '0'; //unbookable
        } else {
            $equipment->equ_status = '1'; //bookable
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

        // Change Equipment current quantity and status
        $equipment = Equipment::find($booking->equ_id);
        $equipment->equ_current_qnt += $booking->booking_qnt;
        $equipment->equ_status = '1'; //bookable
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

        // Change Equipment current quantity and status
        $equipment = Equipment::find($booking->equ_id);
        $equipment->equ_current_qnt += $booking->booking_qnt;
        $equipment->equ_status = '1'; //bookable
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
        $booking_qnt = $request->get('booking_qnt');
        $booking_start = $request->get('booking_start');
        $booking_end = $request->get('booking_end');

        $booking = Booking::find($id);
        $equipment = Equipment::find($booking->equ_id);

        if ($booking_qnt > $equipment->equ_current_qnt) {
            return response()->json([
                'success' => false,
                'message' => 'Error: Can\'t update this booking because booking quantity is greater than current quantity in storage.'
            ]);
        }

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

        // Change Booking quantity and period
        $old_booking_qnt = $booking->booking_qnt;
        $booking->booking_qnt = $booking_qnt;
        $booking->booking_start = $booking_start;
        $booking->booking_end = $booking_end;
        $booking->save();

        // Change equipment current qnt and status
        $equipment->equ_current_qnt += $old_booking_qnt;
        $equipment->equ_current_qnt -= $booking_qnt;
        if ($equipment->equ_current_qnt == 0) {
            $equipment->equ_status = '0'; //unbookable
        } else {
            $equipment->equ_status = '1'; //bookable
        }
        $equipment->save();

        \Session::flash('success', 'Booking(ID: B-' . $booking->id . ') period was changed successfully.');

        return response()->json([
            'success' => true
        ]);
    }
}

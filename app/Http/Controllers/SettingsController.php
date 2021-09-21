<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use App\Models\User;
use Auth;

class SettingsController extends Controller
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
    
    public function index()
    {
        $userId = Auth::user()->id;
        
        $user = User::where('id', $userId)->get()->first();

        return view('pages.settings', compact(
                'user'
            )
        );
    }

    public function storeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'email' => 'required|email:rfc,dns'
        ]);
        
        if ($validator->fails()){
            return redirect(url()->previous())->withErrors($validator)->withInput();
        }
        
        storeUser($request);
        return redirect()->route('settings');
    }

    public function storePassword(Request $request)
    {
        $result = storeUserPassword($request);

        if ($result) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function removeCurrentUser()
    {
        // Remove user
        $result = removeUser(Auth::user()->id);
        
        if ($result) {
            // Logout automatically
            Auth::logout();
            return redirect('/login');
        } else {
            return redirect(url()->previous())
                                ->withErrors('Internal Error: Cannot remove this user just now!')
                                ->withInput();
        }
    }
}

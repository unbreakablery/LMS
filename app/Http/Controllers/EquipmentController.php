<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Equipment;
use App\Models\User;
use Auth;

class EquipmentController extends Controller
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
        $equipments = getAllEquipment();
        
        $nl_equipment_class = 'active';

        return view('pages.equipment', compact('equipments', 'nl_equipment_class'));
    }

    public function indexForManage()
    {
        $equipments = getAllEquipment();
        
        $nl_equipment_class = 'active';

        return view('pages.manage-equipment', compact('equipments', 'nl_equipment_class'));
    }

    public function getEquipment(Request $request)
    {
        $id = $request->get('id');

        $equipment = getEquipmentById($id);

        return response()->json([
            'success' => !empty($equipment),
            'equipment' => $equipment
        ]);
    }

    public function storeEquipment(Request $request)
    {
        $input = $request->all();

        $equipment = Equipment::where('equ_code', $input['equ_code'])->get()->first();
        if (!empty($equipment)) {
            return response()->json([
                'success' => false,
                'message' => 'Error: Equipment (CODE: ' . $input['equ_code'] . ') exists already.'
            ]);
        }
  
        if ($image = $request->file('equ_image')) {
            $destinationPath = 'images/equipments/';
            $equImage = $input['equ_code'] . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $equImage);
            $input['equ_image'] = "$equImage";
        }

        $input['equ_status'] = '0';

        Equipment::create($input);

        \Session::flash('success', 'New equipment(CODE: ' . $input['equ_code'] . ') was created successfully.');

        return response()->json([
            'success' => true
        ]);
    }

    public function updateEquipment(Request $request)
    {
        $input = $request->all();
        
        // Check if equipment with new code exists already
        $equipment = Equipment::where('equ_code', $input['equ_code'])->get()->first();
        if (($input['equ_old_code'] != $input['equ_code']) && !empty($equipment)) {
            return response()->json([
                'success' => false,
                'message' => 'Error: Equipment(CODE: ' . $input['equ_code'] . ') already exists.'
            ]);
        }

        // Check if equipment for updating exists already
        $equipment = Equipment::find($input['equ_id']);
        if (empty($equipment)) {
            return response()->json([
                'success' => false,
                'message' => 'Error: Equipment (ID: ' . $input['equ_id'] . ') for updating not exists.'
            ]);
        }
          
        if ($image = $request->file('equ_image')) {
            $destinationPath = 'images/equipments/';
            $equImage = $input['equ_code'] . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $equImage);
            $input['equ_image'] = "$equImage";

            $equipment->equ_image = $input['equ_image'];
        } else {
            if ($input['equ_old_code'] != $input['equ_code']) {
                if ($equipment->equ_image) {
                    $extension = pathinfo('images/equipments/' . $equipment->equ_image, PATHINFO_EXTENSION);
                    rename('images/equipments/' . $equipment->equ_image, 'images/equipments/' . $input['equ_code'] . '.' . $extension);
                    $equipment->equ_image = $input['equ_code'] . '.' . $extension;
                }
            }
        }

        $equipment->equ_code = $input['equ_code'];
        $equipment->equ_name = $input['equ_name'];
        $equipment->equ_desc = $input['equ_desc'];
        $equipment->cat_id = $input['cat_id'];
        
        $equipment->save();

        \Session::flash('success', 'Equipment(CODE: ' . $input['equ_code'] . ') was updated successfully.');

        return response()->json([
            'success' => true
        ]);
    }

    public function removeEquipment(Request $request)
    {
        $eId = $request->get('id');
        $equipment = Equipment::find($eId);
        $eCode = $equipment->equ_code;

        if (empty($equipment)) {
            return response()->json([
                'success' => false,
                'message' => 'Error: Equipment(ID: ' . $eId . ') not exists.'
            ]);
        }

        if (!empty($equipment->equ_image)) {
            unlink('images/equipments/' . $equipment->equ_image);
        }
  
        $equipment->delete();

        \Session::flash('success', 'Equipment(CODE: ' . $eCode . ') was deleted successfully.');

        return response()->json([
            'success' => true
        ]);
    }
}

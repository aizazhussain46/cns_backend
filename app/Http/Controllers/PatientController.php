<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\Status;
use App\Patient;
use App\Schedule;
use Illuminate\Support\Facades\Auth;
use Validator;
class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('register','login','logout');
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patient = Patient::leftJoin('users', 'patients.user_id', '=', 'users.id')
        ->leftJoin('users as fo', 'patients.field_officer_id', '=', 'fo.id')
        ->leftJoin('statuses', 'patients.status_id', '=', 'statuses.id')
        ->select('patients.*','users.name as doctor','users.clinic', 'statuses.status', 'fo.id as fo_id', 'fo.name as fo')
        ->orderBy('patients.id', 'desc')
        ->get();
        return $patient;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
			'user_id' => 'required',
			'p_name' => 'required',
			'p_cnic' => 'required', 
			'p_mobile_no' => 'required', 
			'p_address' => 'required',
            'status_id' => 'required',
            'field_officer_id' => 'required',
            'district_id' => 'required'
		]); 
		if ($validator->fails()) { 

			return response()->json([
			'success' => false,
			'errors' => $validator->errors()
		
		]); 

		}
        
		$input = $request->all(); 
        $pat = Patient::create($input);
        
        $fo = User::find($pat->field_officer_id);
        /* SMS send code */

        /* save logs to database */
        $type = 'patient register';
        $fields = array(
            'patient_name'=> $pat->p_name, 
            'patient_mobile_no'=> $pat->p_mobile_no,
            'field_officer'=> $fo->name, 
            'fo_mobile_no'=> $fo->mobile_no,
            'type'=> $type
        );

        $schedule = Schedule::create($fields);



		$patient = Patient::where('patients.id', $pat->id)->leftJoin('users', 'patients.user_id', '=', 'users.id')
        ->leftJoin('statuses', 'patients.status_id', '=', 'statuses.id')
        ->select('patients.*','users.name as doctor','users.clinic', 'statuses.status')
        ->first();
		return response()->json([
			'success' => true,
			'data' => $patient
		],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $patient = Patient::where('patients.id', $id)->leftJoin('users', 'patients.user_id', '=', 'users.id')
        ->leftJoin('statuses', 'patients.status_id', '=', 'statuses.id')
        ->select('patients.*','users.name as doctor','users.clinic', 'statuses.status')
        ->get();
		return response()->json([
			'success' => true,
			'data' => $patient
		],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [ 
			'user_id' => 'required',
			'p_name' => 'required',
			'p_cnic' => 'required', 
			'p_mobile_no' => 'required', 
			'p_address' => 'required',
            'status_id' => 'required',
            'field_officer_id' => 'required',
            'district_id' => 'required'
		]); 
		if ($validator->fails()) { 

			return response()->json([
			'success' => false,
			'errors' => $validator->errors()
		
		]); 

		}

		$input = $request->all(); 
        $pat = Patient::where('id', $id)->update($input); 
		$patient = Patient::where('patients.id', $id)->leftJoin('users', 'patients.user_id', '=', 'users.id')
        ->leftJoin('statuses', 'patients.status_id', '=', 'statuses.id')
        ->select('patients.*','users.name as doctor','users.clinic', 'statuses.status')
        ->first();
		return response()->json([
			'success' => true,
			'data' => $patient
		],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return (Patient::find($id)->delete()) 
                ? [ 'response_status' => true, 'message' => 'Patient has been deleted' ] 
                : [ 'response_status' => false, 'message' => 'Patient cannot delete' ];
    }


    public function add_patient_record(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [ 
			'supporter_name' => 'required',
			'supporter_type' => 'required',
			'supporter_contact' => 'required', 
			'referred_by' => 'required', 
			'father_name' => 'required',
            'gender' => 'required',
            'age' => 'required', 
			'occupation' => 'required', 
			'dob' => 'required',
			'reg_date' => 'required'    
		]); 
		if ($validator->fails()) { 

			return response()->json([
			'success' => false,
			'errors' => $validator->errors()
		
		]); 

		}

		$input = $request->all(); 
        $pat = Patient::where('id', $id)->update($input); 
		$patient = Patient::where('patients.id', $id)->leftJoin('users', 'patients.user_id', '=', 'users.id')
        ->leftJoin('statuses', 'patients.status_id', '=', 'statuses.id')
        ->select('patients.*','users.name as doctor','users.clinic', 'statuses.status')
        ->first();
		return response()->json([
			'success' => true,
			'data' => $patient
		],200);
    }
    public function show_patients_by_field_officer(){
        $user = Auth::user();
        $patient = Patient::where('patients.field_officer_id', $user->id)
        ->leftJoin('users', 'patients.user_id', '=', 'users.id')
        ->leftJoin('users as fo', 'patients.field_officer_id', '=', 'fo.id')
        ->leftJoin('statuses', 'patients.status_id', '=', 'statuses.id')
        ->select('patients.*','users.name as doctor','users.clinic', 'fo.name as field officer', 'statuses.status')
        ->orderBy('patients.id', 'desc')
        ->get();
        return $patient;
    }
}

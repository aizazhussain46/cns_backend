<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Status;
use App\Diagnosis;
use App\Patient;
use Illuminate\Support\Facades\Auth;
use Validator;
class DiagnosisController extends Controller
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
        $diagnosis = Diagnosis::all();
        return response()->json([
            'success' => true,
            'data' => $diagnosis
        ],200);
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
        $user = Auth::user();
        
        $input = $request->all();
        $input['field_officer_id'] = $user->id; 
        $diagnosis = Diagnosis::create($input);
        if($diagnosis){
            return response()->json([
                'success' => true,
                'data' => $diagnosis
            ],200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $diagnosis = Diagnosis::where('patient_id', $id)
        ->leftJoin('users', 'diagnoses.field_officer_id', '=', 'users.id')
        ->leftJoin('patients', 'diagnoses.patient_id', '=', 'patients.id')
        ->select('diagnoses.*','users.name as field officer','patients.p_name as patient')->first();
		return response()->json([
			'success' => true,
			'data' => $diagnosis
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
        $input = $request->all(); 
        $update = Diagnosis::where('id', $id)->update($input);
        if($update){
            $diagnosis = Diagnosis::find($id);
            return response()->json([
                'success' => true,
                'data' => $diagnosis
            ],200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return (Diagnosis::find($id)->delete()) 
                ? [ 'response_status' => true, 'message' => 'Diagnosis has been deleted' ] 
                : [ 'response_status' => false, 'message' => 'Diagnosis cannot delete' ];
    }
}

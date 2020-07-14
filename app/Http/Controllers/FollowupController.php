<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\Status;
use App\Diagnosis;
use App\Followup;
use App\Investigation;
use App\Treatment;
use App\Patient;
use Illuminate\Support\Facades\Auth;
use Validator;
class FollowupController extends Controller
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
        $data = array();
        $followup = Followup::leftJoin('users', 'followups.field_officer_id', '=', 'users.id')
        ->leftJoin('patients', 'followups.patient_id', '=', 'patients.id')
        ->select('followups.*','users.name as field officer','patients.p_name as patient')->get();
        foreach($followup as $fup){
            $inv = Investigation::where('followup_id', $fup->id)->get();
            $tr = Treatment::where('followup_id', $fup->id)->get();
            $fup->investigation = $inv;
            $fup->treatment = $tr;
            $data[] = $fup;
        }
		return response()->json([
			'success' => true,
			'data' => $data
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
        $record = $request->all();
        $user = Auth::user();
        $f_up = array("patient_id"=>$record['patient_id'], "visits"=>$record['visits'], 'field_officer_id'=>$user->id);
        if(!empty($record['treatment_outcome'])){
            $f_up['treatment_outcome'] = $record['treatment_outcome'];
            $pat = Patient::where('id', $record['patient_id'])->update(["status_id"=> 3]);
        }
        
        $followup = Followup::create($f_up);
        $inv = array(
            'followup_id'=>$followup->id, 
            'date'=>$record['date'], 
            'examination_type'=>$record['examination_type'],
            'lab_no'=>$record['lab_no'],
            'lab_name'=>$record['lab_name'],
            'result'=>$record['result'],
            'cxr'=>$record['cxr'],
            'weight'=>$record['weight']
            );
        $investigation = Investigation::create($inv);
        
        foreach($record['treatment'] as $treat){
            $tr = array(
                'followup_id'=>$followup->id,
                'regimen_type'=>$treat['regimen_type'],
                'drug_regimen'=>$treat['drug_regimen'],
                'dosage'=>$treat['dosage'],
                'tablets'=>$treat['tablets'],
                'taken_with'=>$treat['taken_with']
            );
            $treatment = Treatment::create($tr);
        }
        
        return response()->json([
			'success' => true,
			'msg' => 'Record Added Successfully'
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
        $data = array();
        $followup = Followup::where('followups.id', $id)
        ->leftJoin('users', 'followups.field_officer_id', '=', 'users.id')
        ->leftJoin('patients', 'followups.patient_id', '=', 'patients.id')
        ->select('followups.*','users.name as field officer','patients.p_name as patient')->get();
        foreach($followup as $fup){
            $inv = Investigation::where('followup_id', $fup->id)->get();
            $tr = Treatment::where('followup_id', $fup->id)->get();
            $fup->investigation = $inv;
            $fup->treatment = $tr;
            $data[] = $fup;
        }
		return response()->json([
			'success' => true,
			'data' => $data
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
        $record = $request->all();
        $user = Auth::user();
        $f_up = array("patient_id"=>$record['patient_id'], "visits"=>$record['visits'], 'field_officer_id'=>$user->id);
        $followup = Followup::where('id', $id)->update($f_up);
        $inv = array(
            'date'=>$record['date'], 
            'examination_type'=>$record['examination_type'],
            'lab_no'=>$record['lab_no'],
            'lab_name'=>$record['lab_name'],
            'result'=>$record['result'],
            'cxr'=>$record['cxr'],
            'weight'=>$record['weight']
            );
        $investigation = Investigation::where('followup_id', $id)->update($inv);
        $trr = Treatment::where('followup_id', $id)->get();
        $treatment = $record['treatment'];
        foreach($trr as $key=>$tre){
            $get_tr = $treatment[$key];
                $tr = array(
                'regimen_type'=>$get_tr['regimen_type'],
                'drug_regimen'=>$get_tr['drug_regimen'],
                'dosage'=>$get_tr['dosage'],
                'tablets'=>$get_tr['tablets'],
                'taken_with'=>$get_tr['taken_with']
            );
            $update = Treatment::where('id', $tre->id)->update($tr);
        }
        
        return response()->json([
			'success' => true,
			'msg' => 'Record Updated Successfully'
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
        $followup = Followup::where('id', $id)->delete();
        $inv = Investigation::where('followup_id', $id)->delete();
        $tr = Treatment::where('followup_id', $id)->delete();
        if($followup && $inv && $tr){
            $msg = array('response_status' => true, 'message' => 'Follow up has been deleted');
        }
        else{
            $msg = array('response_status' => false, 'message' => 'Follow up cannot delete');
        }
        return response()->json($msg);
    }

    public function followups_by_patient($id)
    {
        $patient = Patient::where('patients.id', $id)
        ->leftJoin('users', 'patients.user_id', '=', 'users.id')
        ->leftJoin('users as fo', 'patients.field_officer_id', '=', 'fo.id')
        ->leftJoin('statuses', 'patients.status_id', '=', 'statuses.id')
        ->select('patients.*','users.name as doctor', 'statuses.status', 'fo.id as fo_id', 'fo.name as fo')
        ->orderBy('patients.id', 'desc')
        ->first();

        $diagnosis = Diagnosis::where('patient_id', $id)->first();
        $patient->diagnosis = $diagnosis;
        $data = array();
        $followup = Followup::where('followups.patient_id', $id)
        ->leftJoin('users', 'followups.field_officer_id', '=', 'users.id')
        ->select('followups.*','users.name as field officer')->get();
        foreach($followup as $fup){
            $inv = Investigation::where('followup_id', $fup->id)->get();
            $tr = Treatment::where('followup_id', $fup->id)->get();
            $fup->investigation = $inv;
            $fup->treatment = $tr;
            $data[] = $fup;
        }
        $patient->followup = $data;
        return $patient;
        
    }

}

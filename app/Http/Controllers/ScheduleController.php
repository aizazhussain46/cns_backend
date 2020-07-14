<?php

namespace App\Http\Controllers;
date_default_timezone_set("Asia/karachi");
use Illuminate\Http\Request;
use App\User;   
use App\Role;
use App\Status;
use App\Patient;
use App\Diagnosis;
use App\Followup;
use App\Investigation;
use App\Treatment;
use App\District;
use App\Schedule;
class ScheduleController extends Controller
{
    public function index(){
        $current_date = date("Y-m-d");
        $patient = Patient::where('patients.status_id', 1)->orWhere('patients.status_id', 2)
        ->leftJoin('users', 'patients.field_officer_id', '=', 'users.id')
        ->select('patients.*','users.name as field_officer','users.mobile_no')
        ->orderBy('id', 'desc')->get(); 
        
        foreach($patient as $pat){
            
            $flag = false;
            $patient_name = $pat->p_name;
            $patient_mobile_no = $pat->p_mobile_no;
            $field_officer = $pat->field_officer;
            $fo_mobile_no = $pat->mobile_no;
            $type = 'followup';
            
            $first_rem = date("Y-m-d", strtotime($pat->created_at." +2 months"));
            $first_rem_week = date("Y-m-d", strtotime($first_rem." -1 week"));
            $first_rem_days = date("Y-m-d", strtotime($first_rem." -2 days"));

            $second_rem = date("Y-m-d", strtotime($pat->created_at." +5 months"));
            $second_rem_week = date("Y-m-d", strtotime($second_rem." -1 week"));
            $second_rem_days = date("Y-m-d", strtotime($second_rem." -2 days"));

            $third_rem = date("Y-m-d", strtotime($pat->created_at." +6 months"));
            $third_rem_week = date("Y-m-d", strtotime($third_rem." -1 week"));
            $third_rem_days = date("Y-m-d", strtotime($third_rem." -2 days"));

            // echo "Current_date : ".$current_date;
            // echo "<br>week date : ".$first_rem_week;
            // die;

            if($first_rem_week == $current_date || $first_rem_days == $current_date){
                $followup = "second";
                $flag = true;
            }
            else if($second_rem_week == $current_date || $second_rem_days == $current_date){
                $followup = "third";
                $flag = true;
            }
            else if($third_rem_week == $current_date || $third_rem_days == $current_date){
                $followup = "fourth";
                $flag = true;
            }

            if($flag){
                /* SMS send code */

                /* save logs to database */
                $fields = array(
                    'patient_name'=> $patient_name, 
                    'patient_mobile_no'=> $patient_mobile_no, 
                    'field_officer'=> $field_officer, 
                    'fo_mobile_no'=> $fo_mobile_no,
                    'followup'=> $followup,
                    'type'=> $type
                );

                $schedule = Schedule::create($fields);
            }
            
        }
    }
}

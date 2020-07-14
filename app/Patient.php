<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = ['user_id', 'p_name', 'p_cnic', 'p_mobile_no', 'p_address', 'status_id', 'case_no',
    'supporter_name', 'supporter_type', 'supporter_contact', 'referred_by', 'father_name', 'gender', 
    'age', 'occupation', 'dob', 'reg_date', 'field_officer_id', 'district_id'];
}

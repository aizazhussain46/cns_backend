<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    protected $fillable = ['patient_id', 'contact_of_ptb_case', 'diabetes', 'malnutrition', 'hiv_inf_aids', 'smoking', 
    'hcw', 'risk_f_other', 'pulmonary', 'e_pulmonary', 'pleura', 'lymph_node', 'abdomen', 'bones', 
    'd_site_other', 'histology', 'x_ray', 'u_sound', 'mri', 'eptb_other', 'tb_patient_type', 
    'bacteriology_confirmed', 'clinically_diagnosed', 'retreatment', 'field_officer_id'];
}

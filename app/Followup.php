<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Followup extends Model
{
    protected $fillable = ['patient_id', 'visits', 'treatment_outcome', 'field_officer_id'];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Investigation extends Model
{
    protected $fillable = ['followup_id', 'date', 'examination_type', 'lab_no', 'lab_name', 'result', 'cxr', 'weight'];
}

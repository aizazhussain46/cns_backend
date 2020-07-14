<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    protected $fillable = ['followup_id', 'regimen_type', 'drug_regimen', 'dosage', 'tablets', 'taken_with'];
}

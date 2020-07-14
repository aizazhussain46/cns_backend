<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sms_log extends Model
{
    protected $fillable = ['mobile_no', 'message', 'response'];
}

<?php

use Illuminate\Database\Eloquent\Model;

class HealthRecord extends Model {
    protected $table = 'health_records';
    
    public $timestamps = false;

    protected $fillable = [
        'record_id',
        'patient_name',
        'patient_id',
        'gender',
        'status',
        'record_type',
        'date',
        'blood_pressure',
        'pulse',
        'weight',
        'height',
        'bmi',
        'attending_doctor',
        'note',
        'attachment_url'
    ];
}

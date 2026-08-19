<?php
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $table = 'patients';
 
    public $timestamps = false; 

    protected $fillable = [
        'patient_id', 
        'first_name', 
        'last_name', 
        'gender', 
        'dob', 
        'phone', 
        'address',
        'status'
    ];
}
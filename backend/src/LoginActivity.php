<?php
use Illuminate\Database\Eloquent\Model;

class LoginActivity extends Model {
    protected $table = 'login_activities';
    public $timestamps = false;
    protected $fillable = ['user_id', 'device_name', 'browser', 'ip_address', 'session_id', 'created_at'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}

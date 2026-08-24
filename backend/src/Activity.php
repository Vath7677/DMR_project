<?php

use Illuminate\Database\Eloquent\Model;

class Activity extends Model {
    protected $table = 'system_activities';
    public $timestamps = false;

    protected $fillable = [
        'type',
        'title',
        'description',
        'actor_name',
        'icon_type',
        'created_at'
    ];

    public static function log($type, $title, $description, $actorName = null, $iconType = 'record') {
        try {
            return self::create([
                'type' => $type,
                'title' => $title,
                'description' => $description,
                'actor_name' => $actorName,
                'icon_type' => $iconType,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            error_log('Failed to log activity: ' . $e->getMessage());
            return null;
        }
    }
}

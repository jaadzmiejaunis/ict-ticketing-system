<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserStatusLog extends Model
{
    // Allows these fields to be saved to the database
    protected $fillable = ['user_id', 'admin_id', 'new_status', 'reason'];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    protected $fillable = ['user_id', 'login_at', 'logout_at'];

    // This links the log to the specific user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

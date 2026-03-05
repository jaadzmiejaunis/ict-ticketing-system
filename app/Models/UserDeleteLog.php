<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDeleteLog extends Model
{
    // These allow the system to save data after a user is permanently deleted
    protected $fillable = [
        'user_name',
        'user_email',
        'admin_id',
        'reason'
    ];

    // This links the delete record to the Admin who did it
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}

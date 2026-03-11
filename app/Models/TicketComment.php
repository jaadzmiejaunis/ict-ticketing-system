<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketComment extends Model
{
    // parent_id MUST be here to fix the NULL database issue
    protected $fillable = ['ticket_id', 'user_id', 'comment', 'parent_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function replies() {
        return $this->hasMany(TicketComment::class, 'parent_id')->oldest();
    }
}

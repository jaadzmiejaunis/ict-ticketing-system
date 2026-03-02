<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    // Allow these fields to be filled by the form
    protected $fillable = [
        'user_id',
        'reporter_name',
        'title',
        'description',
        'category',
        'priority',
        'status',
        'due_date',
        'assigned_to',
        'assigned_by',
    ];

    // Relationship: A ticket belongs to a User (Staff)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Allows you to use $ticket->assignee->name in your Blade files!
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}

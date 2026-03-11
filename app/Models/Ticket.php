<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

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
        'resolved_by',
    ];

    // Relationship: A ticket belongs to a User (Staff)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // The person currently doing the work
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // The person who assigned the work
    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    // Inside class Ticket extends Model
    public function wasRecentlyAssigned()
    {
        return $this->status === 'Assigned' && !is_null($this->assigned_to);
    }

    // app/Models/Ticket.php

    public function comments()
    {
        // Filter to only get top-level comments (where parent_id is null)
        return $this->hasMany(TicketComment::class)->whereNull('parent_id')->latest();
    }
}

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
        'resolved_by',
    ];

    // Relationship: A ticket belongs to a User (Staff)
    public function user()
    {
        return $this->belongsTo(User::class);
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
}

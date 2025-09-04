<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_id',
        'user_id',
        'category_id',
        'status_id',
        'department_id',
        'assigned_to',
        'title',
        'description',
        'location',
        'priority',
        'resolved_at',
        'resolution_notes'
    ];

    protected $casts = [
        'resolved_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($complaint) {
            if (empty($complaint->complaint_id)) {
                $year = date('Y');
                $lastComplaint = static::where('complaint_id', 'like', "CMP-{$year}-%")
                    ->orderBy('complaint_id', 'desc')
                    ->first();
                
                if ($lastComplaint) {
                    // Extract the number from the last complaint ID
                    $lastNumber = (int) substr($lastComplaint->complaint_id, -4);
                    $nextNumber = $lastNumber + 1;
                } else {
                    $nextNumber = 1;
                }
                
                $complaint->complaint_id = 'CMP-' . $year . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(ComplaintCategory::class, 'category_id');
    }

    public function status()
    {
        return $this->belongsTo(ComplaintStatus::class, 'status_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class);
    }
}

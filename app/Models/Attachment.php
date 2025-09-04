<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'mime_type'
    ];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'email',
        'phone',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, Staff::class);
    }
}

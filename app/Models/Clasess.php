<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Clasess extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'classes';

    protected $fillable = [
        'grade_id',
        'name',
        'type',
        'capacity',
        'note',
    ];

    public function admissions()
    {
        return $this->hasMany(Admission::class, 'class_id');
    }

    public function students()
    {
        return $this->hasMany(\App\Models\User::class, 'class_id');
    }
}

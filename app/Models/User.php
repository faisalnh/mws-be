<?php

namespace App\Models;

use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $guard_name = 'sanctum';

    protected $primaryKey = 'id';  // primary key integer
    public $incrementing = true;   // auto increment
    protected $keyType = 'int';    // tipe integer

    protected $fillable = [
        'uuid',
        'name',
        'email',
        'password',
        'class_id',
    ];

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->uuid)) {
                $user->uuid = (string) Str::uuid();
            }
        });
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_uuid', 'uuid');
    }

    public function children()
    {
        return $this->belongsToMany(User::class, 'parents_students', 'parent_uuid', 'student_uuid')
            ->withPivot(['relationship', 'can_view_portfolio', 'can_receive_reports'])
            ->withTimestamps();
    }

    public function parents()
    {
        return $this->belongsToMany(User::class, 'parents_students', 'student_uuid', 'parent_uuid')
            ->withPivot(['relationship', 'can_view_portfolio', 'can_receive_reports'])
            ->withTimestamps();
    }

    public function class()
    {
        return $this->belongsTo(Clasess::class, 'class_id', 'id');
    }


    public function teachingClasses()
    {
        return $this->belongsToMany(Clasess::class, 'class_teachers', 'teacher_uuid', 'class_uuid')
            ->withPivot(['role'])
            ->withTimestamps();
    }
}
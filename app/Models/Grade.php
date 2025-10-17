<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Grade extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Gunakan UUID sebagai primary key
     */
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * Nama tabel (opsional jika nama model ≠ nama tabel)
     */
    protected $table = 'grades';

    /**
     * Kolom yang bisa diisi secara massal
     */
    protected $fillable = [
        'name',
        'level',
    ];

    /**
     * Casting kolom
     */
    protected $casts = [
        'level' => 'integer',
    ];

    /**
     * Boot untuk otomatis generate UUID saat create
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (! $model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Relasi ke model Class (Grade memiliki banyak Class)
     */
    public function classes()
    {
        return $this->hasMany(Clasess::class, 'grade_id');
    }
}

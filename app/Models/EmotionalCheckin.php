<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmotionalCheckin extends Model
{
    use HasFactory;

    protected $table = 'emotional_checkins';
    public $incrementing = false; // karena UUID
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'role',
        'mood',
        'internal_weather',
        'presence_level',
        'capasity_level',
        'note',
        'checked_in_at',
        'energy_level',
        'balance',
        'load',
        'readiness',
        'contact_id',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
        'presence_level' => 'integer',
        'capasity_level' => 'integer',
        'mood' => 'array',
    ];

    /**
     * 🚀 Otomatis buat UUID dan isi contact_id = 'no_need' jika kosong
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            // Generate UUID kalau belum ada
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }

            // Jika contact_id kosong → isi 'no_need'
            if (empty($model->contact_id)) {
                $model->contact_id = 'no_need';
            }
        });

        static::updating(function ($model) {
            // Saat update, tetap ubah ke 'no_need' jika kosong
            if (empty($model->contact_id)) {
                $model->contact_id = 'no_need';
            }
        });
    }

    /**
     * Relasi ke user yang melakukan check-in
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'uuid');
    }



    /**
     * Relasi ke contact/penanggung jawab
     */
    public function contact()
    {
        return $this->belongsTo(User::class, 'contact_id', 'id');
    }

    /**
     * Accessor untuk memastikan nilai 'no_need' dikembalikan apa adanya
     */
    public function getContactAttribute()
    {
        if ($this->contact_id === 'no_need') {
            return ['id' => 'no_need', 'name' => 'No Need'];
        }

        $contact = $this->contact()->first();
        return $contact ? ['id' => $contact->id, 'name' => $contact->name] : null;
    }

    /**
     * Accessor tambahan: label mood dengan emoji
     */
    public function getMoodLabelAttribute()
    {
        $moods = (array) $this->mood;

        $labels = array_map(function ($mood) {
            return match ($mood) {
                'very_happy' => '😊 Very Happy',
                'happy' => '🙂 Happy',
                'neutral' => '😐 Neutral',
                'sad' => '😢 Sad',
                'stressed' => '😣 Stressed',
                'angry' => '😡 Angry',
                default => ucfirst($mood),
            };
        }, $moods);

        return implode(', ', $labels);
    }
}
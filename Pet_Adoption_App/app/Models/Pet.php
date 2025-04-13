<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'species',
        'breed',
        'age',
        'gender',
        'description',
        'image',
        'status',
        'user_id',
    ];

    protected static function booted()
    {
        static::updating(function ($pet) {
            if ($pet->isDirty('status') && $pet->status === 'adopted') {
                // When pet is marked as adopted, approve the adoption request
                $pet->adoptionRequests()
                    ->where('status', 'pending')
                    ->update(['status' => 'approved']);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function adoptionRequests()
    {
        return $this->hasMany(AdoptionRequest::class);
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('images/pet-placeholder.jpg');
        }
        
        // Check if it's already a full URL
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        
        return asset('storage/' . $this->image);
    }
}

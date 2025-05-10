<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdoptionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'pet_id',
        'user_id',
        'message',
        'status'
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::created(function ($request) {
            // When request is created, mark pet as pending
            $request->pet->update(['status' => 'pending']);
        });

        static::updated(function ($request) {
            // When request is approved, mark pet as adopted
            if ($request->status === 'approved') {
                $request->pet->update([
                    'status' => 'adopted',
                    'user_id' => $request->user_id
                ]);
                
                // Reject all other pending requests for this pet
                AdoptionRequest::where('pet_id', $request->pet_id)
                    ->where('id', '!=', $request->id)
                    ->where('status', 'pending')
                    ->update(['status' => 'rejected']);
            }
        });
    }
}

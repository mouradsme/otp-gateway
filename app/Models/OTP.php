<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class OTP extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'phone',
        'otp',
        'expiry_datetime'
    ];

    // Cast expiry_datetime to Carbon instance
    protected $dates = [
        'expiry_datetime',
    ];

    // Mutator to hash the OTP before saving to the database
    public function setOtpAttribute($value)
    {
        $this->attributes['otp'] = Hash::make($value);
    }

    // Helper method to check if an OTP is valid
    public function isValid($otp)
    {
        // Check if the hashed OTP matches and if the expiry date is in the future
        return Hash::check($otp, $this->otp) && Carbon::parse($this->expiry_datetime)->isFuture();
    }
}

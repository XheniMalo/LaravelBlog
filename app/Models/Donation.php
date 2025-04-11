<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $table = 'donations'; 
    protected $fillable = [
        'user_id',
        'amount',
        'currency',
        'payment_status',
        'stripe_session_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Znck\Eloquent\Traits\BelongsToThrough;

class Transaction extends Model
{
    use BelongsToThrough;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function wallet()
    {
        return $this->belongsToThrough(Wallet::class, User::class);
    }

    public function booking()
    {
        return $this->hasMany(Booking::class, 'transaction_id' , 'id');
    }

    public function client()
    {
        return $this->belongsToThrough(Client::class, User::class);
    }
}

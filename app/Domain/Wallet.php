<?php

namespace App\Domain;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'balance',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
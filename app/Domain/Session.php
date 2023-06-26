<?php

namespace App\Domain;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $fillable = [
        'client_id', 'token', 'confirmed',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
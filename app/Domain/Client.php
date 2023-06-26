<?php

namespace App\Domain;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'document', 'names', 'email', 'cellphone',
    ];

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }
}

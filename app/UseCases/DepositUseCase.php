<?php

namespace App\UseCases;

use App\Domain\Client;
use App\Domain\Wallet;

class DepositUseCase
{
    public function execute(Client $client, float $value): void
    {
        $wallet = $client->wallet;

        if ($wallet !== null) {
            $wallet->balance += $value;
            $wallet->save();
        } else {
            $wallet = new Wallet();
            $wallet->client_id = $client->id;
            $wallet->balance = $value;
            $wallet->save();
        }
    }
}

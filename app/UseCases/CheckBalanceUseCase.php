<?php

namespace App\UseCases;

use App\Domain\Client;

class CheckBalanceUseCase
{
    public function execute(Client $client): float
    {
        return $client->wallet->balance;
    }
}
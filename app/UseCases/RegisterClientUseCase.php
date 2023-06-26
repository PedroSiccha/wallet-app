<?php

namespace App\UseCases;

use App\Domain\Client;

class RegisterClientUseCase
{
    public function execute(array $data): Client
    {
        return Client::create($data);
    }
}
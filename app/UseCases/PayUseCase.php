<?php

namespace App\UseCases;

use App\Domain\Client;
use App\Domain\Session;
use App\Mail\PaymentConfirmationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PayUseCase
{
    public function initiatePayment(Client $client): Session
    {
      $token = Str::random(6);

      Mail::to($client->email)->send(new PaymentConfirmationMail($token));

        return Session::create([
            'client_id' => $client->id,
            'token' => $token,
        ]);
    }

    public function confirmPayment(Session $session, string $token): void
    {
        $client = $session->client;
        $wallet = $client->wallet;

        if ($token === $session->token) {
            
            $wallet->balance -= 1;
            $wallet->save();

            
            $session->is_confirmed = true;
            $session->save();
        } else {
            throw new \Exception('El token de confirmación es inválido.');
        }
    }
}
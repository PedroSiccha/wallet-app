<?php

namespace App\Http\Controllers;

use App\Domain\Client;
use App\Domain\Session;
use App\UseCases\RegisterClientUseCase;
use App\UseCases\DepositUseCase;
use App\UseCases\PayUseCase;
use App\UseCases\CheckBalanceUseCase;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function register(Request $request, RegisterClientUseCase $registerClientUseCase)
    {
        $data = $request->validate([
            'document' => 'required',
            'names' => 'required',
            'email' => 'required|email',
            'cellphone' => 'required',
        ]);

        $client = $registerClientUseCase->execute($data);

        return response()->json([
            'message' => 'Client registered successfully',
            'client' => $client,
        ], 201);
    }

    public function deposit(Request $request, DepositUseCase $depositUseCase)
    {
        $data = $request->validate([
            'document' => 'required',
            'cellphone' => 'required',
            'value' => 'required|numeric',
        ]);

        $client = Client::where('document', $data['document'])
            ->where('cellphone', $data['cellphone'])
            ->first();

        if (!$client) {
            return response()->json([
                'error' => 'client_not_found',
                'message' => 'Client not found',
            ], 404);
        }

        $depositUseCase->execute($client, $data['value']);

        return response()->json([
            'message' => 'Deposit successful',
            'wallet' => $client->wallet,
        ]);
    }

    public function pay(Request $request, PayUseCase $payUseCase)
    {
        $data = $request->validate([
            'document' => 'required',
            'cellphone' => 'required',
        ]);

        $client = Client::where('document', $data['document'])
            ->where('cellphone', $data['cellphone'])
            ->first();

        if (!$client) {
            return response()->json([
                'error' => 'client_not_found',
                'message' => 'Client not found',
            ], 404);
        }

        $session = $payUseCase->initiatePayment($client);

        return response()->json([
            'message' => 'Payment initiated',
            'session_id' => $session->id,
        ]);
    }

    public function confirm(Request $request, PayUseCase $payUseCase)
    {
        $data = $request->validate([
            'session_id' => 'required|exists:sessions,id',
            'token' => 'required|digits:6',
        ]);

        $session = Session::find($data['session_id']);

        if (!$session) {
            return response()->json([
                'error' => 'session_not_found',
                'message' => 'Session not found',
            ], 404);
        }

        $payUseCase->confirmPayment($session, $data['token']);

        return response()->json([
            'message' => 'Payment confirmed',
            'wallet' => $session->client->wallet,
        ]);
    }

    public function balance(Request $request, CheckBalanceUseCase $checkBalanceUseCase)
    {
        $data = $request->validate([
            'document' => 'required',
            'cellphone' => 'required',
        ]);

        $client = Client::where('document', $data['document'])
            ->where('cellphone', $data['cellphone'])
            ->first();

        if (!$client) {
            return response()->json([
                'error' => 'client_not_found',
                'message' => 'Client not found',
            ], 404);
        }

        $balance = $checkBalanceUseCase->execute($client);

        return response()->json([
            'balance' => $balance,
        ]);
    }
}

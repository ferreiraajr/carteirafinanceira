<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepositRequest;
use App\Services\TransactionService;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DepositController extends Controller
{
    public function __construct(
        private readonly WalletService $walletService,
        private readonly TransactionService $transactionService
    ) {}

    public function create(): Response
    {
        return Inertia::render('Deposit/DepositCreate');
    }

    public function store(DepositRequest $request)
    {
        $user = Auth::user();
        $amount = $request->validated('amount');

        try {
            $this->walletService->deposit($user->wallet->id, $amount);

            $this->transactionService->createTransaction([
                'wallet_id' => $user->wallet->id,
                'type' => 'deposit',
                'amount' => $amount,
            ]);

            return redirect()->route('dashboard')->with('success', 'Depósito realizado com sucesso!');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}

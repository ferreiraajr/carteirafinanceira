<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferRequest;
use App\Services\WalletService;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use App\Models\User;

class TransferController extends Controller
{
    public function __construct(
        private readonly WalletService      $walletService,
        private readonly TransactionService $transactionService
    )
    {
    }

    public function create(): Response
    {
        return Inertia::render('Transfer/TransferCreate');
    }

    public function store(TransferRequest $request)
    {
        $user = Auth::user();
        $amount = $request->validated('amount');
        $recipientId = $request->validated('recipient');

        try {
            $recipientWallet = $this->walletService->getWalletByUserId($recipientId);

            if (!$recipientWallet) {
                return response()->json(['message' => 'Carteira do destinatário não encontrada.'], 404);
            }

            $this->walletService->transfer($user->wallet->id, $recipientWallet->id, $amount);

            $this->transactionService->createTransaction([
                'wallet_id' => $user->wallet->id,
                'wallet_id_from' => $user->wallet->id,
                'wallet_id_to' => $recipientWallet->id,
                'type' => 'transfer',
                'amount' => $amount,
            ]);

            return Inertia::render('Transfer/TransferCreate', [
                'success' => 'Transferência realizada com sucesso!',
            ]);
        } catch (\Exception $e) {
            return Inertia::render('Transfer/TransferCreate', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}

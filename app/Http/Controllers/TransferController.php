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
        $users = User::where('id', '!=', Auth::id())->get();

        return Inertia::render('Transfer/TransferCreate', [
            'users' => $users
        ]);
    }

    public function store(TransferRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $amount = $request->validated('amount');
        $recipientId = $request->validated('recipient');

        try {
            $recipientWallet = $this->walletService->getWalletByUserId($recipientId);

            if (!$recipientWallet) {
                return redirect()->back()->with('error', 'Carteira do destinatário não encontrada.');
            }

            $this->walletService->transfer($user->wallet->id, $recipientWallet->id, $amount);

            $this->transactionService->createTransaction([
                'wallet_id' => $user->wallet->id,
                'wallet_id_from' => $user->wallet->id,
                'wallet_id_to' => $recipientWallet->id,
                'type' => 'transfer',
                'amount' => $amount,
            ]);

            return redirect()->route('dashboard')->with('success', 'Transferência realizada com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}

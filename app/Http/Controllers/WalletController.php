<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\WalletService;
use Illuminate\Http\Request;
use App\Http\Requests\FindWalletByEmailRequest;
use Inertia\Inertia;

class WalletController extends Controller
{
    public function __construct(
        private readonly WalletService $walletService,
    )
    {
    }
    public function findByEmail(FindWalletByEmailRequest $request)
    {
        $email = $request->validated('email');
        $wallet = $this->walletService->getWalletByEmail($email);

        if (!$wallet || !$wallet->user) {
            return response()->json([
                'message' => 'Carteira não encontrada',
            ], 404);
        }

        return response()->json([
            'recipient' => $wallet->user->id,
            'recipient_name' => $wallet->user->name,
        ]);
    }

    public function reverse($transactionId)
    {
        try {
            $this->walletService->reverseTransaction($transactionId);

            return back()->with('success', 'Transação revertida com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}

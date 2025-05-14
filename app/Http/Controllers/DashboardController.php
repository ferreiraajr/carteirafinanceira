<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth()->user()->load('wallet');

        $walletId = $user->wallet->id;

        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $depositCount = $user->wallet->transactions()
            ->where('type', 'deposit')
            ->where('reverted', false)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();
        $transferCount = Transaction::where('type', 'transfer')
            ->where('wallet_id_from', $walletId)
            ->where('reverted', false)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();

        $lastDeposits = $user->wallet->transactions()
            ->where('type', 'deposit')
            ->where('reverted', false)
            ->orderByDesc('created_at')
            ->take(10)
            ->get()
            ->map(function ($deposit) {
                return [
                    'id' => $deposit->id,
                    'type' => $deposit->type,
                    'amount' => $deposit->amount,
                    'formatted_amount' => 'R$ ' . number_format($deposit->amount, 2, ',', '.'),
                    'created_at' => $deposit->created_at,
                    'formatted_created_at' => $deposit->created_at->format('d/m/Y H:i:s'),
                ];
            })
            ->toArray();

        $lastTransfers = Transaction::where('type', 'transfer')
            ->where('wallet_id_from', $walletId)
            ->where('reverted', false)
            ->orderByDesc('created_at')
            ->take(10)
            ->get()
            ->map(function ($transfer) {
                return [
                    'id' => $transfer->id,
                    'type' => $transfer->type,
                    'amount' => $transfer->amount,
                    'formatted_amount' => 'R$ ' . number_format($transfer->amount, 2, ',', '.'),
                    'created_at' => $transfer->created_at,
                    'formatted_created_at' => $transfer->created_at->format('d/m/Y H:i:s'),
                ];
            })
            ->toArray();

        return inertia('Dashboard', [
            'user' => $user,
            'balance' => $user->wallet->getFormattedBalanceAttribute,
            'depositCount' => $depositCount,
            'transferCount' => $transferCount,
            'lastDeposits' => $lastDeposits,
            'lastTransfers' => $lastTransfers,
        ]);
    }
}

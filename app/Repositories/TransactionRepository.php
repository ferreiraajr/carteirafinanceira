<?php
namespace App\Repositories;
use App\Models\Transaction;

class TransactionRepository
{
    public function create(array $data): Transaction
    {
        return Transaction::create($data);
    }

    public function findById(string $id): ?Transaction
    {
        return Transaction::find($id);
    }

    public function forWallet(string $walletId)
    {
        return Transaction::where('wallet_id', $walletId)->get();
    }

    public function update(Transaction $transaction, array $data): Transaction
    {
        $transaction->update($data);
        return $transaction;
    }
}

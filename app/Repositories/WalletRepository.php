<?php
namespace App\Repositories;
use App\Models\Wallet;
class WalletRepository
{
    public function findByUserId(int $userId): ?Wallet
    {
        return Wallet::where('user_id', $userId)->first();
    }

    public function findById(string $id): ?Wallet
    {
        return Wallet::find($id);
    }

    public function create(array $data): Wallet
    {
        return Wallet::create($data);
    }

    public function update(Wallet $wallet, array $data): Wallet
    {
        $wallet->update($data);
        return $wallet;
    }

}

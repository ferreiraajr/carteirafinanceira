<?php
namespace App\Repositories;
use App\Models\User;
use App\Models\Wallet;
class WalletRepository
{
    public function findByUserId(int $userId): ?Wallet
    {
        return Wallet::where('user_id', $userId)->first();
    }
    public function findByEmail(string $email): ?Wallet
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return null;
        }

        return $user->wallet;
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

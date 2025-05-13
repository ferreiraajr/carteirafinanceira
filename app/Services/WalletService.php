<?php
namespace App\Services;

use App\Repositories\WalletRepository;

class WalletService
{
    protected $walletRepository;

    public function __construct(WalletRepository $walletRepository)
    {
        $this->walletRepository = $walletRepository;
    }

    public function getWalletByUserId(int $userId)
    {
        return $this->walletRepository->findByUserId($userId);
    }

    public function getWalletById(string $id)
    {
        return $this->walletRepository->findById($id);
    }

    public function createWallet(array $data)
    {
        return $this->walletRepository->create($data);
    }

    public function updateWallet(string $id, array $data)
    {
        $wallet = $this->getWalletById($id);
        if (!$wallet) {
            throw new \Exception("Carteira não encontrada para o ID: {$id}");
        }
        return $this->walletRepository->update($wallet, $data);
    }

    public function deposit(string $walletId, float $amount): void
    {
        $wallet = $this->getWalletById($walletId);
        if (!$wallet) {
            throw new \Exception("Carteira não encontrada para o ID: {$walletId}");
        }
        $wallet->deposit($amount);
    }

    public function withdraw(string $walletId, float $amount): void
    {
        $wallet = $this->getWalletById($walletId);
        if (!$wallet) {
            throw new \Exception("Carteira não encontrada para o ID: {$walletId}");
        }
        $wallet->withdraw($amount);
    }

    public function transfer(string $walletIdFrom, string $walletIdTo, float $amount): void
    {
        $walletFrom = $this->getWalletById($walletIdFrom);
        $walletTo = $this->getWalletById($walletIdTo);

        if (!$walletFrom || !$walletTo) {
            throw new \Exception("Carteira de origem ou destino não encontrada.");
        }

        if ($walletFrom->balance < $amount) {
            throw new \Exception("Saldo insuficiente.");
        }

        $walletFrom->withdraw($amount);
        $walletTo->deposit($amount);
    }
}

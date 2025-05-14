<?php
namespace App\Services;

use App\Models\Transaction;
use App\Repositories\WalletRepository;
use Illuminate\Support\Facades\DB;

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

    public function getWalletByEmail(string $email){
        return $this->walletRepository->findByEmail($email);
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
        if ($walletIdFrom === $walletIdTo) {
            throw new \Exception("Não é permitido transferir para a própria carteira.");
        }
        $walletFrom = $this->getWalletById($walletIdFrom);
        $walletTo = $this->getWalletById($walletIdTo);

        if (!$walletFrom || !$walletTo) {
            throw new \Exception("Carteira de origem ou destino não encontrada.");
        }
        if ($walletFrom->balance < $amount) {
            throw new \Exception("Saldo insuficiente.");
        }
        if ($walletFrom->user_id === $walletTo->user_id) {
            throw new \Exception("Não é permitido transferir para a própria carteira.");
        }
        $walletFrom->withdraw($amount);
        $walletTo->deposit($amount);
    }

    public function reverseTransaction(string $transactionId): void
    {
        $transaction = app(Transaction::class)->find($transactionId);
        if (!$transaction) {
            throw new \Exception("Transação não encontrada.");
        }
        if ($transaction->reverted) {
            throw new \Exception("Esta transação já foi revertida.");
        }
        if (!in_array($transaction->type, ['transfer', 'deposit'])) {
            throw new \Exception("Apenas depósitos e transferências podem ser revertidos.");
        }
        if ($transaction->type === 'transfer') {
            $walletFrom = $this->getWalletById($transaction->wallet_id_from);
            $walletTo = $this->getWalletById($transaction->wallet_id_to);

            if (!$walletFrom || !$walletTo) {
                throw new \Exception("Carteira de origem ou destino não encontrada.");
            }
            if ($walletTo->balance < $transaction->amount) {
                throw new \Exception("A carteira de destino não possui saldo suficiente para a reversão.");
            }
        } elseif ($transaction->type === 'deposit') {
            $wallet = $this->getWalletById($transaction->wallet_id);

            if (!$wallet) {
                throw new \Exception("Carteira não encontrada.");
            }
            if ($wallet->balance < $transaction->amount) {
                throw new \Exception("A carteira não possui saldo suficiente para a reversão.");
            }
        }

        DB::beginTransaction();
        try {
            if ($transaction->type === 'transfer') {
                $walletTo->withdraw($transaction->amount);
                $walletFrom->deposit($transaction->amount);

                app(TransactionService::class)->createTransaction([
                    'wallet_id' => $walletTo->id,
                    'wallet_id_from' => $walletTo->id,
                    'wallet_id_to' => $walletFrom->id,
                    'type' => 'reversal',
                    'amount' => $transaction->amount,
                ]);
            } elseif ($transaction->type === 'deposit') {
                $wallet->withdraw($transaction->amount);

                app(TransactionService::class)->createTransaction([
                    'wallet_id' => $wallet->id,
                    'wallet_id_from' => null,
                    'wallet_id_to' => null,
                    'type' => 'reversal',
                    'amount' => $transaction->amount,
                ]);
            }
            $transaction->reverted = true;
            $transaction->reverted_at = now();
            $transaction->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

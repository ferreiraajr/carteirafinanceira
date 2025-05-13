<?php
namespace App\Services;

use App\Repositories\TransactionRepository;
use App\Models\Transaction;

class TransactionService
{
    public function __construct(private TransactionRepository $transactionRepository) {}

    public function createTransaction(array $data): Transaction
    {
        return $this->transactionRepository->create($data);
    }

    public function getTransactionById(string $id): ?Transaction
    {
        return $this->transactionRepository->findById($id);
    }

    public function getTransactionsForWallet(string $walletId)
    {
        return $this->transactionRepository->forWallet($walletId);
    }

    public function updateTransaction(string $id, array $data): Transaction
    {
        $transaction = $this->getTransactionById($id);
        if (!$transaction) {
            throw new \Exception("Transação não encontrada para o ID: {$id}");
        }
        return $this->transactionRepository->update($transaction, $data);
    }
}

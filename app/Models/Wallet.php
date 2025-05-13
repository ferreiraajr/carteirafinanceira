<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;


class Wallet extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $appends = ['formatted_balance'];

    protected $fillable = [
        'user_id',
        'balance',
    ];
    protected $casts = [
        'balance' => 'decimal:2',
    ];
    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->primaryKey} = Uuid::uuid4();
        });
    }
    /**
     * Relação com o usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Verifica se há saldo suficiente para uma transação
     */
    public function hasSufficientBalance(float $amount): bool
    {
        return $this->balance >= $amount;
    }
    /**
     * Realisa um depósito na carteira
     */
    public function deposit(float $amount): void
    {
        $this->balance += $amount;
        $this->save();
    }
    /**
     * Realiza um saque na carteira
     */
    public function withdraw(float $amount): void
    {
        if (!$this->hasSufficientBalance($amount)) {
            throw new \Exception('Saldo insuficiente');
        }

        $this->balance -= $amount;
        $this->save();
    }
    /**
     * Formata o saldo da carteira
     */
    protected function getFormattedBalanceAttribute(): Attribute
    {
        return Attribute::make(
            get: fn () => 'R$ ' . number_format($this->balance, 2, ',', '.')
        );
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'wallet_id');
    }

}

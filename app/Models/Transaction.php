<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Transaction extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'wallet_id',
        'wallet_id_from',
        'wallet_id_to',
        'type',
        'amount',
        'reverted',
        'reverted_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'reverted' => 'boolean',
        'reverted_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->primaryKey} = Uuid::uuid4();
        });
    }
    public function wallet()
    {
        return $this->belongsTo(\App\Models\Wallet::class, 'wallet_id');
    }
    public function walletFrom()
    {
        return $this->belongsTo(\App\Models\Wallet::class, 'wallet_id_from');
    }
    public function walletTo()
    {
        return $this->belongsTo(\App\Models\Wallet::class, 'wallet_id_to');
    }
}

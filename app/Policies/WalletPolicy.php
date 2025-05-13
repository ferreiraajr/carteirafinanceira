<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Auth\Access\Response;

class WalletPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Wallet $wallet): bool
    {
        return $wallet->user_id === $user->id;
    }
    public function create(User $user): bool
    {
        return false;
    }
    public function update(User $user, Wallet $wallet): bool
    {
        return $wallet->user_id === $user->id;
    }
    public function delete(User $user, Wallet $wallet): bool
    {
        return false;
    }
    public function restore(User $user, Wallet $wallet): bool
    {
        return false;
    }
    public function forceDelete(User $user, Wallet $wallet): bool
    {
        return false;
    }
}

<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Wallet;
use App\Repository\WalletRepository;

class WalletService
{
    public function __construct(
        private readonly WalletRepository $walletRepository
    )
    {
    }

    public function findWalletsForUser(User $user): array {
        return $this->walletRepository->findWalletsForUser($user);
    }

    public function getUserAccessOnWallet(User $user, Wallet $wallet): int {
        $xUserWallet = null;

        try {
            $xUserWallet = $this->xUserWalletRepository->getUserAccessOnWallet($user, $wallet);
        } catch (\Exception $e) {}
        return $xUserWallet;
    }
}

<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Wallet;
use App\Entity\XUserWallet;
use App\Repository\WalletRepository;
use App\Repository\XUserWalletRepository;

class WalletService
{
    public function __construct(
        private readonly WalletRepository $walletRepository,
        private readonly XUserWalletRepository $xUserWalletRepository
    )
    {
    }

    public function findWalletsForUser(User $user): array {
        return $this->walletRepository->findWalletsForUser($user);
    }

    public function getUserAccessOnWallet(User $user, Wallet $wallet): null|XUserWallet {
        $xUserWallet = null;

        try {
            $xUserWallet = $this->xUserWalletRepository->getUserAccessOnWallet($user, $wallet);
        } catch (\Exception $e) {}
        return $xUserWallet;
    }
}

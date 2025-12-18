<?php

namespace App\Service;

use App\Entity\Expense;
use App\Entity\User;
use App\Repository\WalletRepository;

class ExpenseService
{
    public function __construct(
        private readonly ExpenseRepository $expenseRepository
    )
    {
        parent::__construct($registry, Expense::class);
    }
    public function findExpensesForWallet(Wallet $wallet, int $page, int $limit): array {
        return
            $this
                ->createQueryBuilder("a")
                ->innerJoin("e.wallet", "w", "WITH", "w.is_deleted = false AND w.id = :walletId")
                ->andWhere("e.is_deleted = false")
                ->orderBy ("e.createdDate", "DESC")
                ->setMaxResults($limit)
                ->setFirstResult(($page - 1) * $limit)
                ->setParameter("walletId", $wallet->getId())
                ->getQuery()
                ->getResult();
    }
}

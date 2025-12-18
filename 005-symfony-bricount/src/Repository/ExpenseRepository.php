<?php

namespace App\Repository;

use App\Entity\Expense;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Expense>
 */
class ExpenseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
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

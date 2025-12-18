<?php

namespace App\Controller\Wallets;

use App\Entity\Wallet;
use App\Service\ExpenseService;
use App\Service\WalletService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ShowController extends AbstractController
{
    #[Route('/wallets/{uid}', name: 'wallets_show', methods: ['GET'])]
    public function index(
        #[MapEntity(mapping: ['uid' => 'uid'])]
        Wallet $wallet,

        ExpenseService $expenseService,
        WalletService $walletService,
    ): Response
    {
        // vérifier l'accès de l'utilisateur courant au wallet indentifié par l'ID

        // 1. récupérer l'utilisateur courant
        $connectedUser = $this->getUser();

        // 2. transformer  l'ID du wallet, en wallet objet

        // 3. faire la vérification d'accès via le WalletService



        return $this->render('wallets/show/index.html.twig', [
            'controller_name' => 'ShowController',
            'uid' => $wallet->getUid(),
        ]);
    }
}

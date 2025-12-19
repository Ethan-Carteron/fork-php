<?php

namespace App\Controller\Wallets;

use App\Entity\Wallet;
use App\Service\ExpenseService;
use App\Service\WalletService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

final class ShowController extends AbstractController
{
    #[Route('/wallets/{uid}', name: 'wallets_show', methods: ['GET'])]
    public function index(
        #[MapEntity(mapping: ['uid' => 'uid'])]
        Wallet $wallet,

        ExpenseService $expenseService,
        WalletService $walletService,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] int $limit = 15,
    ): Response
    {
        // vérifier l'accès de l'utilisateur courant au wallet indentifié par l'ID

        // 1. récupérer l'utilisateur courant
        $connectedUser = $this->getUser();

        // 2. transformer  l'ID du wallet, en wallet objet

        // 3. faire la vérification d'accès via le WalletService
        $xUserWallet = $walletService->getUserAccessOnWallet($connectedUser, $wallet);

        // si l'utilisateur courant n'a pas acccès au wallet avec un message d'erreur
        // pour savoir s'il à accès, vérifier que $xUser n'est pas null

        if (true === is_null($xUserWallet)) {
            // vu que xUserWallet est null, on vire le user

            // 1. setup un message d'erreur
            $this->addFlash("error", "vous n'avez pas accès ) ce portefeuille");

            // 2. rediriger vers la liste des wallets
            return $this->redirectToRoute('wallets_list');
        }

        //récupérer les expenses du wallet
        $expenses = $expenseService->findExpensesForWallet($wallet, $page, $limit);

        //nombre total de dépenses du wallet
        $nbTotalExpenses = $expenseService->countExpensesForWallet($wallet);

        $maxPaginationPage = ceil($nbTotalExpenses / $limit);

        return $this->render('wallets/show/index.html.twig', [
            'expenses' => $expenses,
            'wallet' => $wallet,
            'controller_name' => 'ShowController',
            'uid' => $wallet->getUid(),
            "maxPaginationPage" => $maxPaginationPage,
            'limit' => $limit,
        ]);
    }
}

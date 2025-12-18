<?php

namespace App\Controller\Wallets;

use App\Service\WalletService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ListController extends AbstractController
{
    #[Route('/wallets', name: 'wallets_list', methods: ['GET'])]
    public function index(
        WalletService $walletService
    ): Response
    {
        // 1. récupérer l'utilisateur actuellement connecté
        $connectedUser = $this->getUser();

        // 2. Utiliser le walletService, en lui donnant l'utilisateur connecté
        // pour récupérer une liste de wallet
        $wallets = $walletService->findWalletsForUser($connectedUser);

        // 3. renvoyer la vue avec la liste des wallets
        return $this->render('wallets/list/index.html.twig', [
            'wallets' => $wallets,
        ]);
    }
}

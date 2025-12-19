<?php

namespace App\Controller\Wallets;

use App\DTO\WalletDTO;
use App\Form\WalletType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CreateController extends AbstractController
{
    #[Route('/wallets/create', name: 'app_wallets_create', methods: ['GET','POST'])]
    public function index(): Response
    {
        $dto = new WalletDTO();

        $form = $this->createForm(WalletType::class, $dto);

        return $this->render('wallets/create/index.html.twig', [
            'form' => $form
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/game/{id}', name: 'frontend_game_')]
class GameController extends AbstractController
{
    #[Route(path: '', name: 'details', methods: ['GET', 'POST'])]
    public function details(int $id, Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $this->addFlash('success', 'Changes saved.');
            return $this->redirectToRoute('frontend_game_details', ['id' => $id]);
        }

        return $this->render('frontend/game/details.html.twig', [
            'gameId' => $id,
        ]);
    }

    #[Route(path: '/delete', name: 'delete', methods: ['GET', 'POST'])]
    public function delete(int $id, Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $this->addFlash('success', 'Game deactivated successfully.');
            return $this->redirectToRoute('frontend_dashboard_index');
        }

        return $this->render('frontend/game/delete_confirm.html.twig', [
            'gameId' => $id,
        ]);
    }
}

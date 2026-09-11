<?php

declare(strict_types=1);

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/', name: 'frontend_dashboard_')]
class DashboardController extends AbstractController
{
    #[Route(name: 'index', methods: ['GET'])]
    public function __invoke(): Response
    {
        return $this->render('frontend/dashboard/index.html.twig');
    }
}
<?php

declare(strict_types=1);

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route(path: "/", name: "dashboard", methods: ["GET"])]
    public function index(): Response
    {
        return new Response("I am dashboard, bow before me.");
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestAnimationController extends AbstractController
{
    #[Route('/test-animation', name: 'test_animation')]
    public function index(): Response
    {
        return $this->render('test_animation.html.twig');
    }
}

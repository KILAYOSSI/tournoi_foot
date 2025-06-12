<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin-panel", name="admin_panel")
     */
    public function index(): Response
    {
        // Page d'accueil du panneau admin
        return $this->render('admin/index.html.twig');
    }
}

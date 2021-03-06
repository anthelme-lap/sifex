<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestAdminController extends AbstractController
{
    /**
     * @Route("/admin", name="test_admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }
}

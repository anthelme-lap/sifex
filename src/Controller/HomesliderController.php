<?php

namespace App\Controller;

use App\Entity\Homeslider;
use App\Form\HomesliderType;
use App\Repository\HomesliderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/homeslider")
 */
class HomesliderController extends AbstractController
{
    /**
     * @Route("/", name="app_homeslider_index", methods={"GET"})
     */
    public function index(HomesliderRepository $homesliderRepository): Response
    {
        return $this->render('homeslider/index.html.twig', [
            'homesliders' => $homesliderRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_homeslider_new", methods={"GET", "POST"})
     */
    public function new(Request $request, HomesliderRepository $homesliderRepository): Response
    {
        $homeslider = new Homeslider();
        $form = $this->createForm(HomesliderType::class, $homeslider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $homesliderRepository->add($homeslider);
            return $this->redirectToRoute('app_homeslider_index');
        }

        return $this->renderForm('homeslider/new.html.twig', [
            'homeslider' => $homeslider,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_homeslider_show", methods={"GET"})
     */
    public function show(Homeslider $homeslider): Response
    {
        return $this->render('homeslider/show.html.twig', [
            'homeslider' => $homeslider,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_homeslider_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Homeslider $homeslider, HomesliderRepository $homesliderRepository): Response
    {
        $form = $this->createForm(HomesliderType::class, $homeslider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $homesliderRepository->add($homeslider);
            return $this->redirectToRoute('app_homeslider_index');
        }

        return $this->renderForm('homeslider/edit.html.twig', [
            'homeslider' => $homeslider,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_homeslider_delete", methods={"POST"})
     */
    public function delete(Request $request, Homeslider $homeslider, HomesliderRepository $homesliderRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$homeslider->getId(), $request->request->get('_token'))) {
            $homesliderRepository->remove($homeslider);
        }

        return $this->redirectToRoute('app_homeslider_index');
    }
}

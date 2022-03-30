<?php

namespace App\Controller;

use App\Entity\Arrive;
use App\Form\ArriveType;
use App\Repository\ArriveRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ArriveController extends AbstractController
{
    /**
     * @Route("admin/arrive/", name="app_arrive_index", methods={"GET"})
     */
    public function index(ArriveRepository $arriveRepository): Response
    {
        return $this->render('admin/arrive/index.html.twig', [
            'arrives' => $arriveRepository->findAll(),
        ]);
    }

    /**
     * @Route("admin/arrive/new", name="app_arrive_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ArriveRepository $arriveRepository): Response
    {
        $arrive = new Arrive();
        $form = $this->createForm(ArriveType::class, $arrive);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $arriveRepository->add($arrive);
            
            $this->addFlash('success', 'Votre commune d\'arrivé a été crée avec succès');
            return $this->redirectToRoute('app_arrive_new');
        }

        return $this->renderForm('admin/arrive/new.html.twig', [
            'arrive' => $arrive,
            'form' => $form,
        ]);
    }

    /**
     * @Route("admin/arrive/{id}", name="app_arrive_show", methods={"GET"})
     */
    public function show(Arrive $arrive): Response
    {
        return $this->render('admin/arrive/show.html.twig', [
            'arrive' => $arrive,
        ]);
    }

    /**
     * @Route("admin/arrive/{id}/edit", name="app_arrive_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Arrive $arrive, ArriveRepository $arriveRepository): Response
    {
        $form = $this->createForm(ArriveType::class, $arrive);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $arriveRepository->add($arrive);
            return $this->redirectToRoute('app_arrive_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/arrive/edit.html.twig', [
            'arrive' => $arrive,
            'form' => $form,
        ]);
    }

    /**
     * @Route("admin/arrive/{id}", name="app_arrive_delete", methods={"POST"})
     */
    public function delete(Request $request, Arrive $arrive, ArriveRepository $arriveRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        if ($this->isCsrfTokenValid('delete'.$arrive->getId(), $request->request->get('_token'))) {
            $arriveRepository->remove($arrive);
        }

        return $this->redirectToRoute('app_arrive_index', [], Response::HTTP_SEE_OTHER);
    }
}

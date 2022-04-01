<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\Prix;
use App\Entity\Demande;
use App\Entity\Commande;
use App\Form\DemandeType;
use App\Repository\DemandeRepository;
use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DemandeController extends AbstractController
{
    /**
     * @Route("/admin/demande", name="app_demande_index", methods={"GET"})
     */
    public function index(DemandeRepository $demandeRepository): Response
    {
        return $this->render('demande/index.html.twig', [
            'demandes' => $demandeRepository->findAll(),
        ]);
    }
    
    
    /**
     * @Route("/demande/verification", name="app_demande_verification", methods={"GET"})
     */
    public function verification(DemandeRepository $demandeRepository,Prix $prixservice): Response
    {
        
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $departs = $demandeRepository->findDepart($this->getUser()->getId());
        $arrive = $demandeRepository->findArrive($this->getUser()->getId());
        // $demandes = $demandeRepository->findLastDemandeIdUser($this->getUser()->getId());

        $prix = $prixservice->sendPrix($departs,$arrive);
       
        return $this->render('demande/verification.html.twig', [
            'demandes' => $demandeRepository->findLastDemandeUser($this->getUser()->getId()),
            'prix' => $prix
        ]);
    }

    /**
     * @Route("/demande/new", name="app_demande_new", methods={"GET", "POST"})
     */
    public function new(Request $request, DemandeRepository $demandeRepository): Response
    {
        if (!$this->getUser()) 
        {
            $this->addFlash('danger', 'Connectez-vous avant de faire une demande');
            return $this->redirectToRoute('app_home');
        }
        
        $demande = new Demande();
        $user = $this->getUser();
        $demande->setFkuser($user);
        $demande->setCreatedAt(new \DateTimeImmutable('now'));
        $form = $this->createForm(DemandeType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $demandeRepository->add($demande);
            return $this->redirectToRoute('app_demande_verification');
        }

        return $this->renderForm('demande/new.html.twig', [
            'demande' => $demande,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/demande/{id}", name="app_demande_show", methods={"GET"})
     */
    public function show(Demande $demande): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        return $this->render('demande/show.html.twig', [
            'demande' => $demande,
        ]);
    }

    /**
     * @Route("/demande/{id}/edit", name="app_demande_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Demande $demande, DemandeRepository $demandeRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        $form = $this->createForm(DemandeType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $demandeRepository->add($demande);
            return $this->redirectToRoute('app_demande_verification');
        }

        return $this->renderForm('demande/edit.html.twig', [
            'demande' => $demande,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/demande/{id}", name="app_demande_delete", methods={"POST"})
     */
    public function delete(Request $request, Demande $demande, DemandeRepository $demandeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$demande->getId(), $request->request->get('_token'))) {
            $demandeRepository->remove($demande);
        }

        return $this->redirectToRoute('app_demande_index');
    }

     /**
     * @Route("/commande", name="app_order", methods={"GET","POST"})
     */
    public function commandeNew(CommandeRepository $commandeRepository, Prix $prixService,DemandeRepository $demandeRepository): Response
    {
        $departs = $demandeRepository->findDepart($this->getUser()->getId());
        $arrive = $demandeRepository->findArrive($this->getUser()->getId());
        $demande = $demandeRepository->findLastDemandeId($this->getUser()->getId());
        
        $prix = $prixService->sendPrix($departs,$arrive);
        // dd($demande);
        $commande = new Commande();
        $dmd = $demandeRepository->find($demande);
        // dd($dmd);
        $commande->setPrice($prix);
        $commande->setFkuser($this->getUser());
        $commande->setCreatedAt(new \DateTimeImmutable('now'));
        $dmd->setCommande($commande);

        $commandeRepository->add($commande);

        return $this->render('account/order.html.twig');
    }

}

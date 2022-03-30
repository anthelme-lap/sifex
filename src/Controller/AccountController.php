<?php

namespace App\Controller;

use App\Entity\Order;
use App\Services\Prix;
use App\Repository\OrderRepository;
use App\Repository\DemandeRepository;
use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    private $prixService;
    private $demandeRepos;
    private $commandeRepos;

    public function __construct(Prix $prixService,DemandeRepository $demandeRepos,CommandeRepository $commandeRepos)
    {
       $this->prixService = $prixService; 
       $this->demandeRepos = $demandeRepos; 
       $this->commandeRepos = $commandeRepos; 
    }

    /**
     * @Route("/account", name="app_account")
     */
    public function index(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        
        $departs = $this->demandeRepos->findDepart($this->getUser()->getId());
        $arrive = $this->demandeRepos->findArrive($this->getUser()->getId());
        $commandes = $this->commandeRepos->findCommande($this->getUser()->getId());

        $prix = $this->prixService->sendPrix($departs,$arrive);

        // dd($commandes);
        return $this->render('account/index.html.twig',[
            'demandes' => $this->demandeRepos->findLastDemandeUser($this->getUser()->getId()),
            'prix' => $prix,
            'commandes' => $commandes,

        ]);
    }
}

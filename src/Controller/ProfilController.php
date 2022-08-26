<?php

namespace App\Controller;

use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(CommandeRepository $repo): Response
    {
        $user = $this->getUser();
        if ($user == null) {
            // 1ère méthode : redirection vers une autre page
            $this->addFlash("error", "Vous devez être connecté pour accéder à votre profil.");
            return $this->redirectToRoute('app_login');
            // 2ème méthode : affichage d'un template d'erreur personnalisé
            // return $this->render("error/profile_denied.html.twig");
            // 3ème méthode : lancement d'une erreur avec override de template Symfony

        } else {
            # code...
        }
        
        $commande = $repo->findBy(["commande" => $user]);
        // findBy() permet de récupérer des données en précisant des conditions 
        // ici, je précise que l'auteur des commentaires doit correspondre à l'utilisateur actuellement connecté
        return $this->render("commande/index.html.twig", ['commande' => $commande]);
    }      
}

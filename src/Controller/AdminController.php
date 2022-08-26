<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Form\VehiculeType;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }
    #[Route('/admin/vehicule', name: 'admin_vehicule')]
    public function adminVehicule(VehiculeRepository $repo, EntityManagerInterface $manager)
    {
        // on utilise le manager pour récupérer le nom des champs de la table Article
        $champs = $manager->getClassMetadata(Vehicule::class)->getFieldNames();
        // dd($champs); // dd() : dump & die : afficher des infos et arrêter l'exécution du code
        $vehicule = $repo->findAll();
        return $this->render("admin/admin_vehicule.html.twig", ['vehicule' => $vehicule, 'champs' => $champs]);
    }
    #[Route('/admin/vehicule/new', name: 'admin_new_vehicule')]
    #[Route('/admin/vehicule/edit/{id}', name: 'admin_edit_vehicule')]
    public function vehicule_form(Vehicule $vehicule = null, Request $superglobals, EntityManagerInterface $manager)
    {
        if (!$vehicule) { // équivalent à if ($article == null)
            $vehicule = new Vehicule; // je crée un objet Article vide prêt à être rempli
            $vehicule->setDateEnregistrement(new \DateTime()); // ajout de la date seulement à l'insertion d'un article
        }       
        $form = $this->createForm(VehiculeType::class, $vehicule); // je lie le formulaire à $article
        $form->handleRequest($superglobals);
        if ($form->isSubmitted() && $form->isValid()) {        
            $manager->persist($vehicule); // prépare la future requête
            $manager->flush(); // exécute la requête (insertion)
            return $this->redirectToRoute('admin_vehicule');
        }
        return $this->renderForm("admin/vehicule_form.html.twig", ['formVehicule' => $form, 'editMode' => $vehicule->getId() !== NULL]);
    }
    #[Route('/admin/vehicule/delete/{id}', name: 'admin_delete_vehicule')]
    public function vehicule_delete(EntityManagerInterface $manager, VehiculeRepository $repo, $id)
    {
        $vehicule = $repo->find($id);
        $manager->remove($vehicule);
        $manager->flush();
        $this->addFlash('success', "Le véhicule a bien été retiré de la base de données !");
        return $this->redirectToRoute("vehicule_articles");
    }
}

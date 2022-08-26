<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Projet2SymfonyController extends AbstractController
{
    #[Route('/projet2/symfony', name: 'app_projet2_symfony')]
    public function index(): Response
    {
        $vehicule = $repo->findAll();
        return $this->render('projet2_symfony/index.html.twig', [
            'controller_name' => 'Projet2SymfonyController',
            'tabVehicule' => $vehicule
        ]);
    }
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('projet2_symfony/home.html.twig', [
            'title' => 'Accueil'
        ]);
    }
    #[Route('/projet2_symfony/show/{id}', name: 'projet2_symfony_show')]
    public function show($id, VehiculeRepository $repo)
    {
        $vehicule = $repo->find($id);
        return $this->render('projet2_symfony/show.html.twig', ['vehicule' => $vehicule]);
    }
    #[Route('/projet2_symfony/new', name: 'projet2_symfony_create')]
    #[Route('/projet2_symfony/edit/{id}', name: 'projet2_symfony_edit')]
    public function form(Request $superglobals, EntityManagerInterface $manager, Vehicule $vehicule = null)
    {
        if (!$vehicule) { 
            $vehicule = new Vehicule; 
        }       
        $form = $this->createForm(VehiculeFormType::class, $vehicule);
        $form->handleRequest($superglobals);
        if ($form->isSubmitted() && $form->isValid()) {         
            $manager->persist($vehicule); 
            $manager->flush(); 
            return $this->redirectToRoute('projet2_symfony_show', ['id' => $vehicule->getId()]);
        }
        return $this->renderForm("projet2_symfony/form.html.twig", ['formVehicule' => $form, 'editMode' => $vehicule->getId() !== NULL]);
    }
    #[Route('/projet2_symfony/delete/{id}', name: 'projet2_symfony_delete')]
    public function delete(EntityManagerInterface $manager, $id, VehiculeRepository $repo)
    {
        $vehicule = $repo->find($id);
        $manager->remove($vehicule);
        $manager->flush();
        $this->addFlash('success', "Le véhicule a bien été retiré de la base de données !");
        return $this->redirectToRoute("app_projet2_symfony");
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Vehicule;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class VehiculeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=1; $i <= 3; $i++) { 
            # code...
            $vehicule = new Vehicule();
            $vehicule->setTitre("$i")->setMarque("$i")->setModele("$i")->setDescription("$i")->setPhoto("$i")->setPrixJournalier($i)->setDateEnregistrement();
            $manager->persist($vehicule);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}

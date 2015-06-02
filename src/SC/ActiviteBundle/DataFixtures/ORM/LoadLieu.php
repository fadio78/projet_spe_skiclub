<?php

namespace SC\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SC\ActiviteBundle\Entity\Saison;
use SC\ActiviteBundle\Entity\Lieu;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadLieu extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $manager)
  {
  
    $tab = array("alpes huez","monx ventoux","evrest");  
    
    foreach ($tab as $lieu) {
        $monLieu = new Lieu;
        $monLieu->setNomLieu($lieu);
        $manager->persist($monLieu);
        $manager->flush();
    }

  }
  
  public function getOrder()
  {
        return 7; // l'ordre dans lequel les fichiers sont chargés
  }
}
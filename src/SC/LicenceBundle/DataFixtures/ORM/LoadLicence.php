<?php

namespace SC\LicenceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SC\LicenceBundle\Entity\Licence;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadLicence extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $manager)
  {
    // Les noms d'utilisateurs à créer
    $listNames = array('ski alpin', 'ski de fond');

    foreach ($listNames as $name) {
      // On crée la licence
      $licence = new Licence;
      $licence->setTypeLicence($name);
      $licence->setPrixLicence(20);
    
      
      // On le persiste
      $manager->persist($licence);
    }

    // On déclenche l'enregistrement
    $manager->flush();
  }
  public function getOrder()
  {
        return 1; // l'ordre dans lequel les fichiers sont chargés
  }  
}



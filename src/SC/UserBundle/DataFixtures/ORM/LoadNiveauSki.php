<?php

namespace SC\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SC\UserBundle\Entity\NiveauSki;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use \SC\UserBundle\Controller\SecurityController ;

class LoadNiveauSki extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $manager)
  {

    $listNiveaux = array('Pas d\'étoile', 'Flocon', '1ère étoile','2ème étoile','3ème étoile','Etoile de bronze','Etoile d\'or');
    foreach ($listNiveaux as $niveau) 
    {
      $niveauSki = new NiveauSki();
      $niveauSki -> setNiveau($niveau);
      $manager->persist($niveauSki);
    }

    $manager->flush();
  }
  public function getOrder()
  {
        return 0; // l'ordre dans lequel les fichiers sont chargés
  }  
}


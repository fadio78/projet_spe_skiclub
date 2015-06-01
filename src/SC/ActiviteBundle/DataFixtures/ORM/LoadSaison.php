<?php

namespace SC\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SC\ActiviteBundle\Entity\Saison;
use SC\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class LoadSaison extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $manager)
  {
  
    $saison = new Saison;
    $saison->setAnnee(2014);
    $manager->persist($saison);
   
    $saison1 = new Saison;
    $saison1->setAnnee(2015);
    $manager->persist($saison1);
    
    $manager->flush(); 
  }
  
  public function getOrder()
  {
        return 5; // l'ordre dans lequel les fichiers sont chargés
  }
}
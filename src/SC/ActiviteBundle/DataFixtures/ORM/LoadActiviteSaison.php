<?php

namespace SC\ActiviteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SC\ActiviteBundle\Entity\Activite;
use SC\ActiviteBundle\Entity\Saison;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadActiviteSaison extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
    $id = 46;
    $saison = $manager ->getRepository('SCActiviteBundle:Saison') -> findOneBy(array('annee' => '2014'));
    $activites = $manager->getRepository('SCActiviteBundle:Activite')->findAll();
    foreach ($activites as $activite) 
    {
        $saison -> addActivite($activite);
    }
    $manager-> persist($saison);
    $manager->flush();
  }
  public function getOrder()
  {
        return 10; // l'ordre dans lequel les fichiers sont chargés
  }  
}


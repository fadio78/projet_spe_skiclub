<?php

namespace SC\ActiviteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SC\ActiviteBundle\Entity\Activite;
use SC\UserBundle\Entity\User;
use SC\ActiviteBundle\Entity\Lieu;
use SC\ActiviteBundle\Entity\Saison;
use SC\ActiviteBundle\Entity\Sortie;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadSortie extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $user = $manager ->getRepository('SCUserBundle:User') ->findOneBy(array('email' => 'sfr@hotmail.com'));
    $lieux =  $manager->getRepository('SCActiviteBundle:Lieu')->findAll();
    $saison =  $manager->getRepository('SCActiviteBundle:Saison')->findOneByAnnee(2014);
    $activites = $manager->getRepository('SCActiviteBundle:Activite')->findAll();
    
    $i = 0;
    
    foreach ($activites as $activite) {
        foreach ($lieux as $lieu) {
            $i =$i+1;
            $sortie = new Sortie();
            $sortie->setDateSortie($i.' '.$i.' '.$i);
            $sortie->setLieu($lieu);
            $sortie->setActivite($activite);
            $sortie->setUser($user);
            $sortie->setSaison($saison);
            
            $manager->persist($sortie);
            $manager->flush();                        
        }
    }
   $i = 0;
   $saison =  $manager->getRepository('SCActiviteBundle:Saison')->findOneByAnnee(2015); 
     
    foreach ($activites as $activite) {
        foreach ($lieux as $lieu) {
            $i =$i+1;
            $sortie = new Sortie();
            $sortie->setDateSortie($i.' '.$i.' '.$i);
            $sortie->setLieu($lieu);
            $sortie->setActivite($activite);
            $sortie->setUser($user);
            $sortie->setSaison($saison);
            
            $manager->persist($sortie);
            $manager->flush();                        
        }
    }   
  }

  public function getOrder()
  {
        return 8; // l'ordre dans lequel les fichiers sont chargés
  }
  
}  
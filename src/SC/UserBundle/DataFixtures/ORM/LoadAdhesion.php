<?php

namespace SC\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SC\UserBundle\Entity\Adhesion;
use SC\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class LoadAdhesion extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $manager)
  {
    //on recupere tous les saison de la BD  
    $advertRepository = $manager->getRepository('SCActiviteBundle:Saison')->findAll();
    //on recupere tous les users de la BD
    $userRepository = $manager->getRepository('SCUserBundle:User')->findAll();
    //on va creer une adhesion
    foreach ($userRepository as $user) {
        foreach($advertRepository as $saison){
          $adhesion = new Adhesion;
          $adhesion->setAdhesionAnnuel(false);
          //$adhesion->setModalite(0);
          $adhesion->setMontantPaye('0');
          $adhesion->setRemise('0');
          $adhesion->setSaison($saison);
          $adhesion->setUser($user);
          //ajout dans la base
        $manager->persist($adhesion);
        }
        
    }
        $manager->flush(); 
  }
  
  public function getOrder()
  {
        return 6; // l'ordre dans lequel les fichiers sont chargés
  }
}
<?php

namespace SC\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SC\UserBundle\Entity\Enfant;
use SC\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class LoadEnfant extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $manager)
  {
    //on recupere tous les user de la BD  
    $advertRepository = $manager->getRepository('SC\UserBundle\Entity\User')->findAll();
    
    //on va creer un nouvel enfant
    foreach ($advertRepository as $client) {
        $enfant = new Enfant;
        $enfant->setDateNaissance(new \DateTime("2012-07-08")); 
        $enfant->setNiveauSki('bon'); 
        $enfant->setNomEnfant('monNom'); 
        $enfant->setPrenomEnfant('monPrenom');
        $enfant->setUserParent($client);
        
        //ajout dans la base
        $manager->persist($enfant);
    }
        $manager->flush(); 
  }
  
  public function getOrder()
  {
        return 2; // l'ordre dans lequel les fichiers sont chargés
  }
}


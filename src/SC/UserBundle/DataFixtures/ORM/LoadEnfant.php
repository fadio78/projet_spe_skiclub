<?php

namespace SC\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SC\UserBundle\Entity\Enfant;
use SC\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;


class LoadEnfant extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $advertRepository = $manager->getRepository('SC\UserBundle\Entity\User')->findAll();
    
    foreach ($advertRepository as $client) {
        $enfant = new Enfant;
        $enfant->setDateNaissance('17aout'); 
        $enfant->setNiveauSki('bon'); 
        $enfant->setNomEnfant('monNom'); 
        $enfant->setPrenomEnfant('monPrenom');
        $enfant->setUserParent($client);
        
        $manager->persist($enfant);
    }
    /*
        $user = new User;
            
        $user->setEmail('supp@hotmail.com');
        $user->setNom('monNom');
        $user->setPrenom('monPrenom');
        $user->setAdresse('14 rue grenoble');
        $user->setCommune('grenoble');
        $user->setTelephone('06125566333');
        $user->setPassword('azerty');
        $user->setType('ok');
        $user->setValidite(1);
      
        // On ne se sert pas du sel pour l'instant
        $user->setSalt('');
        // On définit uniquement le role ROLE_USER qui est le role de base
        $user->setRoles(array('ROLE_USER'));      

        
        $enfant = new Enfant;
        $enfant->setDateNaissance('17aout'); 
        $enfant->setNiveauSki('bon'); 
        $enfant->setNomEnfant('monNom'); 
        $enfant->setPrenomEnfant('monPrenom');
        $enfant->setUserParent($user);

        $manager->persist($user);
        $manager->persist($enfant);*/
        

        $manager->flush(); 
  }
  public function getOrder()
  {
        return 2; // l'ordre dans lequel les fichiers sont chargés
  }
}


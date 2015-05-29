<?php

namespace SC\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SC\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadUser extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $manager)
  {
    // Les noms d'utilisateurs à créer
    $listNames = array('sgbd@hotmail.com', 'test@hotmail.com', 'sfr@hotmail.com');
    $i = 0;
    foreach ($listNames as $name) {
      // On crée l'utilisateur
      $user = new User;

      // Le nom d'utilisateur et le mot de passe sont identiques
      $user->setEmail($name);
      $user->setNom('monNom');
      $user->setPrenom('monPrenom');
      $user->setAdresse('14 rue grenoble');
      $user->setCommune('grenoble');
      $user->setTelephone('06125566333');
      $user->setPassword('azerty');
      if ($i==2) {
        $user->setType('admin');
      }
      else {
          $user->setType('client');
      }
      $i = $i+1;
      $user->setIsActive(1);
      $user->setIsPrimaire(1);
      
     
      // On ne se sert pas du sel pour l'instant
      $user->setSalt(''); 
      // On définit uniquement le role ROLE_USER qui est le role de base
     // $user->setRoles(array('ROLE_USER'));

      // On le persiste
      $manager->persist($user);
    }

    // On déclenche l'enregistrement
    $manager->flush();
  }
  public function getOrder()
  {
        return 1; // l'ordre dans lequel les fichiers sont chargés
  }  
}


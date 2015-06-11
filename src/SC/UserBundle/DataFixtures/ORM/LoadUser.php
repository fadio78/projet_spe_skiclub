<?php

namespace SC\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SC\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use \SC\UserBundle\Controller\SecurityController ;

class LoadUser extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $manager)
  {
    // Les noms d'utilisateurs à créer
    $listNames = array('sgbd@hotmail.com', 'test@hotmail.com', 'admin@hotmail.com','dupont@gmail.com','bob@gmail.com','alice@gmail.com','john@gmail.com','cris@hotmail.com','leo@hotmail.com','new@hotmail.com','gerard@hotmail.com','jeanne@hotmail.com','marie@hotmail.com','lucie@hotmail.com','barca@hotmail.com','real@hotmail.com','coool@hotmail.com');
    $i = 0;
    foreach ($listNames as $name) {
      // On crée l'utilisateur
      $user = new User;

      // Le nom d'utilisateur et le mot de passe sont identiques
      $user->setEmail($name);
      $user->setNom('nomParent'.$i);
      $user->setPrenom('prenomParent'.$i);
      $user->setAdresse('14 rue grenoble');
      $user->setCommune('grenoble');
      $user->setTelephone('06125566333');
      //création du salt aléatoire
      
      $user->setSalt('796ebe2464'); 
      //correspond au mot de passe azerty
      $user->setPassword('7LttXNaq28TF2GaUMTTx4Mr6i493LWbiWwNXxenBiKwyUuoVBzq0LsUhF6B4z7Ohl8oyc699+dZuCY6l+qMxlg==');
     
      
      if ($i==2) {
        $user->setType('admin');
      }
      else {
          $user->setType('client');
      }
      $i = $i+1;
      $user->setIsActive(1);
      $user->setIsPrimaire(1);
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


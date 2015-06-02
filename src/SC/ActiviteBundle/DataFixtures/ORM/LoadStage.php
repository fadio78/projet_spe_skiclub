<?php

namespace SC\ActiviteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SC\ActiviteBundle\Entity\Stage;
use SC\UserBundle\Entity\User;
use SC\LicenceBundle\Entity\Licence;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadStage extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $user = $manager ->getRepository('SCUserBundle:User') ->findOneBy(array('email' => 'sfr@hotmail.com'));
    $activite = $manager ->getRepository('SCActiviteBundle:Activite') ->findOneByNomActivite('Ski alpin');
    $saison =  $manager->getRepository('SCActiviteBundle:Saison')->findOneByAnnee(2014);
    $lieu =  $manager->getRepository('SCActiviteBundle:Lieu')->findOneByNomLieu('evrest');

    
    $stage1 = new Stage();
    $stage1 ->setActivite($activite);
    $stage1 ->setNomStage('super Stage');
    $stage1 ->setDebutStage('2010-01-01');
    $stage1 ->setFinStage('2010-01-05');
    $stage1 ->setCharges(20);
    $stage1 ->setPrixStage(50);
    $stage1 ->setLieu($lieu);
    $stage1 ->setSaison($saison);
    $stage1 ->setUser($user);
    $manager->persist($stage1);
    
    
    $manager->flush();
      


  }
  public function getOrder()
  {
        return 9; // l'ordre dans lequel les fichiers sont chargés
  }  
}
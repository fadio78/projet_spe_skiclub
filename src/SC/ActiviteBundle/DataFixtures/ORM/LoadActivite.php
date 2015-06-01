<?php

namespace SC\ActiviteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SC\ActiviteBundle\Entity\Activite;
use SC\UserBundle\Entity\User;
use SC\LicenceBundle\Entity\Licence;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadActivite extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $user = $manager ->getRepository('SCUserBundle:User') ->findOneBy(array('email' => 'sfr@hotmail.com'));

    
    $activite1 = new Activite ();
    $activite1->setNomActivite('Ski alpin');
    $activite1->setPrixActivite(150);
    $activite1 ->setUser($user);
    $licence = $manager ->getRepository('SCLicenceBundle:Licence') ->findOneBy(array('typeLicence' =>'ski alpin'));
    $activite1 -> setLicence($licence);
    $manager->persist($activite1);

    $activite2 = new Activite ();
    $activite2->setNomActivite('Ski de fond');
    $activite2->setPrixActivite(200);
    $activite2 ->setUser($user);
    $licence = $manager ->getRepository('SCLicenceBundle:Licence') ->findOneBy(array('typeLicence' => 'ski de fond'));
    $activite2 -> setLicence($licence);
    $manager->persist($activite2);
    
    $activite3 = new Activite ();
    $activite3->setNomActivite('Freestyle');
    $activite3 ->setPrixActivite(100);
    $activite3 ->setUser($user);
    $licence = $manager ->getRepository('SCLicenceBundle:Licence') ->findOneBy(array('typeLicence' => 'ski de fond'));
    $activite3 -> setLicence($licence);
    $manager->persist($activite3);
    
    
    $activite4 = new Activite ();
    $activite4 ->setNomActivite('Fartage de ski');
    $activite4 ->setPrixActivite(30);
    $activite4 ->setUser($user);
    $manager->persist($activite4);
    
    
    $manager->flush();
      


  }
  public function getOrder()
  {
        return 4; // l'ordre dans lequel les fichiers sont chargés
  }  
}
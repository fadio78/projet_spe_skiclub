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
    $listActivite = $manager ->getRepository('SCActiviteBundle:Activite') ->findAll();
    $listSaison =  $manager->getRepository('SCActiviteBundle:Saison')->findAll();
    $lieu =  $manager->getRepository('SCActiviteBundle:Lieu')->findOneByNomLieu('evrest');

    foreach ($listActivite as $activite) {
        foreach ($listSaison as $saison){
            $stage1 = new Stage();
            $stage1 ->setActivite($activite);
            $stage1 ->setNomStage('super Stage de '.$activite->getNomActivite());
            $mois = rand(9,12);
            $stage1 ->setDebutStage($saison->getAnnee().'-'.$mois.'-'.rand(1,15));
            $stage1 ->setFinStage($saison->getAnnee().'-'.$mois.'-'.rand(16,30));
            $stage1 ->setCharges(rand(1,99));
            $stage1 ->setPrixStage(rand(50,199));
            $stage1 ->setLieu($lieu);
            $stage1 ->setSaison($saison);
            $stage1 ->setUser($user);
            $manager->persist($stage1);
        }
    }
    $manager->flush();
      


  
  }
  public function getOrder()
  {
        return 9; // l'ordre dans lequel les fichiers sont chargés
  }  
}
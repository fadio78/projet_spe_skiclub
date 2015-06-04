<?php

namespace SC\ActiviteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SC\ActiviteBundle\Entity\Stage;
use SC\ActiviteBundle\Entity\InscriptionActivite;
use SC\ActiviteBundle\Entity\Activite;
use SC\UserBundle\Entity\User;
use SC\UserBundle\Entity\Enfant;
use SC\LicenceBundle\Entity\Licence;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadInscriptionActivite extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $listEnfant = $manager ->getRepository('SCUserBundle:Enfant') ->findAll();
    $listActivite = $manager ->getRepository('SCActiviteBundle:Activite') ->findAll();
    $listSaison =  $manager->getRepository('SCActiviteBundle:Saison')->findAll();
    $lieu =  $manager->getRepository('SCActiviteBundle:Lieu')->findOneByNomLieu('evrest');
    $aleatoire = 0;
    foreach ($listEnfant as $enfant){
        
    
        foreach ($listActivite as $activite) {
            foreach ($listSaison as $saison){
                $aleatoire = rand(1,3);
                
                if (($aleatoire % 3 )== 0){
                    $inscription = new InscriptionActivite();
                    $inscription ->setActivite($activite);
                    $inscription ->setSaison($saison);
               
                    $inscription ->setEmail($enfant->getUserParent()->getUsername());
                    $inscription ->setPrenomEnfant($enfant->getPrenomEnfant());
                    $inscription ->setNomEnfant($enfant->getNomEnfant());
                    $inscription ->setGroupe('L'.rand(1,3));
                
                
                
                    $manager->persist($inscription);
                }
            }
        }
    }
    $manager->flush();
      


  
  }
  public function getOrder()
  {
        return 11; // l'ordre dans lequel les fichiers sont chargés
  }  
}
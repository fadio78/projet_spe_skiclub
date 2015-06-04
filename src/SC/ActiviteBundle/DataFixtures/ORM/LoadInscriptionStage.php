<?php

namespace SC\ActiviteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SC\ActiviteBundle\Entity\Stage;
use SC\ActiviteBundle\Entity\InscriptionStage;
use SC\ActiviteBundle\Entity\Activite;
use SC\UserBundle\Entity\User;
use SC\UserBundle\Entity\Enfant;
use SC\LicenceBundle\Entity\Licence;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadInscriptionStage extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $listEnfant = $manager ->getRepository('SCUserBundle:Enfant') ->findAll();
    $listStage = $manager ->getRepository('SCActiviteBundle:Stage') ->findAll();
    $listSaison =  $manager->getRepository('SCActiviteBundle:Saison')->findAll();
    $lieu =  $manager->getRepository('SCActiviteBundle:Lieu')->findOneByNomLieu('evrest');
    $aleatoire = 0;
    foreach ($listEnfant as $enfant){
        
    
        foreach ($listStage as $stage) {
            
                $aleatoire = rand(1,2);
                
                if (($aleatoire % 2 )== 0){
                    $inscription = new InscriptionStage();
                    $inscription ->setActivite($stage->getActivite());
                    $inscription ->setSaison($stage->getSaison());
               
                    $inscription ->setUser($enfant->getUserParent());
                    $inscription ->setPrenomEnfant($enfant->getPrenomEnfant());
                    $inscription ->setNomEnfant($enfant->getNomEnfant());
                    $inscription ->setDebutStage($stage->getDebutStage());
                    $inscription ->setFinStage($stage->getFinStage());
                    $inscription ->setPrixPayeStage(0);
                
                
                
                    $manager->persist($inscription);
                }
            
        }
    }
    $manager->flush();
      


  
  }
  public function getOrder()
  {
        return 12; // l'ordre dans lequel les fichiers sont chargés
  }  
}
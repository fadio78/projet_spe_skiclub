<?php

namespace SC\ActiviteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SC\ActiviteBundle\Entity\Stage;
use SC\ActiviteBundle\Entity\InscriptionSortie;
use SC\ActiviteBundle\Entity\InscriptionActivite;
use SC\ActiviteBundle\Entity\Activite;
use SC\UserBundle\Entity\User;
use SC\UserBundle\Entity\Enfant;
use SC\LicenceBundle\Entity\Licence;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadInscriptionSortie extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $manager)
  {
    
    
    $listInscriptionActivite = $manager ->getRepository('SCActiviteBundle:inscriptionActivite') ->findAll();
    $listSaison =  $manager->getRepository('SCActiviteBundle:Saison')->findAll();
    $lieu =  $manager->getRepository('SCActiviteBundle:Lieu')->findOneByNomLieu('evrest');
    $aleatoire = 0;
    foreach ($listInscriptionActivite as $inscriptionActivite){
        $listSortie = $manager ->getRepository('SCActiviteBundle:Sortie') ->findBy(array('activite'=>$inscriptionActivite->getActivite())); 
    
            foreach ($listSortie as $sortie) {
                $aleatoire = rand(1,3);
                    $inscription = new InscriptionSortie();
                    $inscription ->setDateSortie($sortie->getDateSortie());
                    $inscription ->setIdActivite($sortie->getActivite()->getId());
                    $inscription ->setLieu($sortie->getLieu()->getNomLieu());
                    $inscription ->setEmailParent($inscriptionActivite->getEmail());
                    $inscription ->setNomEnfant($inscriptionActivite->getNomEnfant());
                    $inscription ->setPrenomEnfant($inscriptionActivite->getPrenomEnfant());
                    $inscription ->setSaison($inscriptionActivite->getSaison()->getAnnee());
                    $inscription ->setParticipation(0);
                    if (($aleatoire % 3 )== 0){
                        $inscription ->setParticipation(1);
                    }
                    $manager->persist($inscription);
            }
    }          
            
        
    
    $manager->flush();
      


  
  }
  public function getOrder()
  {
        return 13; // l'ordre dans lequel les fichiers sont chargés
  }  
}
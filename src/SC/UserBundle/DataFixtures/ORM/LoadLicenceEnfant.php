<?php

namespace SC\ActiviteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SC\ActiviteBundle\Entity\Stage;
use SC\ActiviteBundle\Entity\InscriptionActivite;
use SC\ActiviteBundle\Entity\Licence;
use SC\UserBundle\Entity\LicenceEnfant;
use SC\UserBundle\Entity\User;
use SC\UserBundle\Entity\Enfant;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadLicenceEnfant extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $saison = $manager -> getRepository ('SCActiviteBundle:Saison') -> findOneByAnnee(2014);
    $listInscritsActivites = $manager ->getRepository('SCActiviteBundle:InscriptionActivite') ->findBy(array('saison' => $saison));
    foreach ($listInscritsActivites as $inscrit )
    {
        $licence = $inscrit -> getActivite() -> getLicence();
        if (isset($licence))
        {
            $licenceEnfantExiste = $manager -> getRepository ('SCUserBundle:LicenceEnfant') -> findOneBy(array('email' => $inscrit -> getEmail(),'prenomEnfant' => ($inscrit -> getPrenomEnfant()),'nomEnfant' => ($inscrit -> getNomEnfant()),'saison' => $saison,'licence' => $inscrit ->getActivite()->getLicence() ));
            if ($licenceEnfantExiste == null)
            {
                $licenceEnfant = new LicenceEnfant();
                $licenceEnfant -> setLicence ($licence);
                $licenceEnfant -> setEmail($inscrit -> getEmail());
                $licenceEnfant -> setPrenomEnfant($inscrit -> getPrenomEnfant());
                $licenceEnfant -> setNomEnfant($inscrit -> getNomEnfant()); 
                $licenceEnfant -> setSaison($saison);
                $manager->persist($licenceEnfant);
                $manager->flush();
                
            }
        }
    }
  }
  public function getOrder()
  {
        return 14; // l'ordre dans lequel les fichiers sont chargés
  } 
  
  public function ajoutLicenceEnfant($licence,$prenom,$nom,$saison,$email)
  {
        

        
        
  }
}
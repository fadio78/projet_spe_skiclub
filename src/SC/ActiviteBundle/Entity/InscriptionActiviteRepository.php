<?php

namespace SC\ActiviteBundle\Entity;

use Doctrine\ORM\EntityRepository;
use SC\UserBundle\Entity\Enfant;
use SC\ActiviteBundle\Entity\Saison;
/**
 * InscriptionActiviteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InscriptionActiviteRepository extends EntityRepository
{
    // vérifie si un utilisateur a déjà inscrit son enfant à une activité donnée pour la saison en cours 
    public function est_Inscrit($inscriptionActivite) 
    {
        
        $qb= $this->createQueryBuilder('i')
         ->where('i.prenomEnfant = :prenomEnfant')
         ->setParameter('prenomEnfant', $inscriptionActivite  -> getPrenomEnfant() )
         -> andWhere  ('i.nomEnfant = :nomEnfant')
         ->setParameter('nomEnfant', $inscriptionActivite -> getNomEnfant() )
         ->andwhere('i.saison = :annee')
         ->setParameter('annee', $inscriptionActivite -> getSaison() -> getAnnee() )
         ->andwhere('i.email = :email')
         ->setParameter('email', $inscriptionActivite -> getEmail() )
         ->andwhere('i.activite = :id')
         ->setParameter('id', $inscriptionActivite -> getActivite() ->getId()  );
        return $qb->getQuery()->getResult();

    }
    
    //retourne la liste des inscriptions de la saison en cours aux activités à partir d'un email donné  
    public function listeDeMesInscriptions($email)
    {
        $saison = new Saison ();
        $year = $saison->connaitreSaison(); 
        $qb= $this->createQueryBuilder('i')
                  ->where('i.email = :email')
                  ->setParameter('email', $email)
                  ->andwhere('i.saison = :annee')
                  ->setParameter('annee', $year);
        
        return $qb->getQuery()->getResult();
        
    }
    
    // retourne la somme des activités à payer à partir d'un email donné pour la saison en cours 
    public function getSommeActivitesApayer($email)
    {
        $somme = 0;
        $saison = new Saison ();
        $year = $saison->connaitreSaison();          
        $qb = $this ->createQueryBuilder('i')
                    ->leftJoin('i.activite', 'a')
                    ->addSelect('a')
                    ->where('i.saison = :annee')
                    ->setParameter('annee', $year)
                    ->andwhere('i.email = :email')
                    ->setParameter('email', $email);
        $liste = $qb  ->getQuery() ->getResult();
        foreach( $liste as $inscri)
        {
            $somme = $somme + ($inscri ->getActivite() -> getPrixActivite());
        }
        return $somme;
    } 
  
    // retourne la somme total à payer à part l'adhésion à partir d'un email donné pour la saison en cours en prenant en compte l'adhesions 
    public function getSommeApayer($email)
    {
        $somme = 0;
        $r = $this -> _em-> getRepository('SCUserBundle:Enfant');
        $liste = $r->findBy(['userParent' => $email]);
        $r = $this -> _em-> getRepository('SCUserBundle:LicenceEnfant');
        //pour chaque enfant, on calcule la somme de licence à payer
        foreach ($liste as $inscri)
        {
            $s = $r -> getSommeLicences($email,$inscri -> getPrenomEnfant(),$inscri -> getNomEnfant());
            $somme = $somme + $s;
            
        }
        $a = $this -> getSommeActivitesApayer($email);
        //on somme le total des licences des enfants avec la somme des prix des activités
        $somme = $somme + $a ;
        //on enleve la remise
        $saison = new Saison ();
        $annee = $saison->connaitreSaison();
        $remise = $this -> _em->getRepository('SCUserBundle:Adhesion')->findOneby(array('saison'=> $annee,'user'=>$email))->getRemise();
        $somme =$somme - $remise;
        return $somme;
    }
    
    
    
    
    
    
    // retourne la somme des activités à payer pour un enfant donné pour la saison en cours 
    public function getSommeActivitesApayerParEnfant($email,$prenom,$nom)
    {
        $somme = 0;
        $saison = new Saison ();
        $year = $saison->connaitreSaison();          
        $qb = $this ->createQueryBuilder('i')
                    ->leftJoin('i.activite', 'a')
                    ->addSelect('a')
                    ->where('i.saison = :annee')
                    ->setParameter('annee', $year)
                    ->andwhere('i.email = :email')
                    -> setParameter('email',$email)
                    ->andwhere('i.prenom = :prenom')
                    ->setParameter('prenom', $prenom)
                    ->andwhere('i.nom = :nom')
                    ->setParameter('nom', $nom);
        $liste = $qb  ->getQuery() ->getResult();
        foreach( $liste as $inscri)
        {
            $somme = $somme + ($inscri ->getActivite() -> getPrixActivite());
        }
        
        
        return $somme;
    }
    
    // retourne la liste des inscriptions à une actvité donné des saisons précédentes 
    public function  inscriptionsSaisons($id) 
    {
        $saison = new Saison ();
        $year = $saison->connaitreSaison();
        $qb = $this->createQueryBuilder('i')
           ->where('i.activite = :id')
           ->setParameter('id', $id)
           ->andWhere('i.saison < :year')
           ->setParameter('year', $year);
        return $qb->getQuery()->getResult();
    }
    
    
    // retourne les inscription à une activité donnée pour la saison en cours
    public function inscriptions($id) 
    {
        $saison = new Saison ();
        $year = $saison->connaitreSaison(); 
        $qb= $this->_em->createQuery('SELECT i from SCActiviteBundle:InscriptionActivite i WHERE i.saison =:annee and i.activite =:id') 
                  ->setParameter('annee',$year)
                  ->setParameter('id', $id);
        return $qb->getResult();

    }
    
    // retourne la liste des mails des inscrits à une activité donnée
    public function getListeMails($id)
    {
        $saison = new Saison ();
        $year = $saison->connaitreSaison(); 
        $qb = $this->_em->createQuery('SELECT  distinct i.email from SCActiviteBundle:InscriptionActivite i WHERE i.activite =:id and i.saison =:annee')
                        ->setParameter('id', $id)
                        ->setParameter('annee', $year);

        $liste =$qb ->getResult();
        return $liste;
    }
}

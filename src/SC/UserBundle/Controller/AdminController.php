<?php
// src/SC/UserBundle/Controller/AdminController.php;

namespace SC\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use SC\UserBundle\Entity\User;
use SC\UserBundle\Entity\Adhesion;
use SC\UserBundle\Form\UserType;
use Doctrine\ORM\EntityRepository;
use SC\ActiviteBundle\Entity\Activite;
use SC\ActiviteBundle\Entity\InscriptionActivite;
use SC\LicenceBundle\Entity\Licence;
use SC\UserBundle\Entity\LicenceEnfant;
use SC\ActiviteBundle\Entity\Saison;


class AdminController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render('SCUserBundle:Admin:index.html.twig');
        
        
    }
    public function listUsersInactifAction(Request $request)
    {
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCUserBundle:User');
           
          $listUsersInactif = $repository->compteInactif();
        
        return $this->render('SCUserBundle:Admin:listUsersInactif.html.twig',
                array('listUsersInactif'=>$listUsersInactif )
                );
        
        
    }
        public function listNoAdminAction(Request $request)
    {
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCUserBundle:User');
          
        
          $listNoAdmin = $repository->findAll();//tous le monde 
         //$listNoAdmin = $repository->noAdmin();//Que les clients 
        
        return $this->render('SCUserBundle:Admin:listNoAdmin.html.twig',
                array('listNoAdmin'=>$listNoAdmin )
                );
        
        
    }
        public function activerCompteAction(Request $request, $email)
    {
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCUserBundle:User');
           
           $repository->activerCompte($email);
        $request->getSession()->getFlashBag()->add('info', 'Compte bien activé ');
        
        $message = \Swift_Message::newInstance()
        
                        
        ->setSubject('Compte activé')
        ->setFrom($request->getSession()->get('email'))
        ->setTo($email)
        ->setBody('Votre Compte a été activité, vous pouvez vous connecter sur skiclub la petite roche')
                        
    ;

    $this->get('mailer')->send($message);
        
        
        return $this->redirect($this->generateUrl('sc_admin_listUsersInactif'));
        
        
    }
            public function supprimerCompteAction(Request $request, $email)
    {
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCUserBundle:User');
           
           $repository->supprimerCompte($email);
        $request->getSession()->getFlashBag()->add('info', 'Compte bien supprimé ');
        return $this->redirect($this->generateUrl('sc_admin_listUsersInactif'));
        
        
    }

           public function activerAdminAction(Request $request, $email)
    {
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCUserBundle:User');
           
           $repository->activerAdmin($email);
        $request->getSession()->getFlashBag()->add('info', 'new Admin ! ');
        return $this->redirect($this->generateUrl('sc_admin_listNoAdmin'));
        
        
    } 
               public function gestionCompteAction(Request $request, $email)
    {
                   
                   
        $saison = new Saison;
        $annee = $saison->connaitreSaison();
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCUserBundle:User');
           
           $user= $repository->findOneby(['email' => $email]);
           
           
        $em = $this->getDoctrine()->getManager();
     
        
        //on le rentre dans la table adhesion si il est pas encore inscrit 
               
        $adhesion = $em->getRepository('SCUserBundle:Adhesion')->findOneby(
                   array('user' => $email,
                         'saison'=> $annee
                   ));
        
        
        if ($adhesion == null){
            
            $saison_actuel = $em->getRepository('SC\ActiviteBundle\Entity\Saison')->find($annee);
            $adhesion = new Adhesion;
            $adhesion->setAdhesionAnnuel(false);
            $adhesion->setModalite(0);
            $adhesion->setMontantPaye(0);
            $adhesion->setRemise(0);
            $adhesion->setSaison($saison_actuel);
            $adhesion->setUser($user);
            $em->persist($adhesion);
            $em->flush();
        }
           
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCUserBundle:Adhesion');
           
           $adhesion = $repository->findOneby(
                   array('user' => $email,
                         'saison'=> $annee
                   ));
           
           //On cherche le montant que l'utilisateur doit au total pour une saison 
           $dette = $em->getRepository('SCActiviteBundle:InscriptionActivite')->getSommeApayer($email);
           
              
           
        return $this->render('SCUserBundle:Admin:gestionCompte.html.twig',
                array('user'=>$user ,
                      'adhesion'=>$adhesion,
                      'dette'=>$dette
                )
                
                );
        
        
    }

               public function ajoutMontantAction(Request $request, $email)
    {

        $saison = new Saison;
        $annee = $saison->connaitreSaison();
        $montant = $_POST['_montant'];
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCUserBundle:Adhesion');
         
           $adhesion = $repository->ajoutMontant($email,$annee,$montant);
              
             
         return  $this->gestionCompteAction( $request, $email);
        
        
    }
              public function ajoutRemiseAction(Request $request, $email)
            {

        $saison = new Saison;
        $annee = $saison->connaitreSaison();
        $montant = $_POST['_remise'];
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCUserBundle:Adhesion');
         
           $adhesion = $repository->ajoutRemise($email,$annee,$montant);
              
             
         return  $this->gestionCompteAction( $request, $email);
        
        
    }
    
    // récupère la liste des inscrits à une activité de la saison en cours pour un email donné
    public function gestionEnfantAction($email)
    {
        $niveauSki = null;
        $em =  $this ->getDoctrine() ->getManager();
        $repository = $em ->getRepository('SCActiviteBundle:InscriptionActivite');

        $listeEnfantsInscrits = $repository -> listeDeMesInscriptions($email); 
        $repository = $em -> getRepository('SCUserBundle:User');
        $user = $repository ->find($email);
        $repository = $em -> getRepository('SCUserBundle:Enfant');
        // pour chaque enfant inscrit à l'activité, on récupère son niveau de ski
        foreach ( $listeEnfantsInscrits as $inscrit )
        {
            $enfant = $repository ->findOneBy(array('userParent' => $user,'prenomEnfant' => $inscrit -> getPrenomEnfant(),'nomEnfant' => $inscrit -> getNomEnfant()));
            $niveauSki[$email.$inscrit->getPrenomEnfant().$inscrit->getNomEnfant()] = $enfant -> getNiveauSki() -> getNiveau();
            
        }
        return $this->render('SCUserBundle:Admin:gestionEnfant.html.twig',array('listeEnfantsInscrits' => $listeEnfantsInscrits,'niveauSki' => $niveauSki));
        
    }

    
        public function adhererAction(Request $request, $email)
        {

        $saison = new Saison;
        $annee = $saison->connaitreSaison();
        $adhesion = $_POST['_adhesion'];
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCUserBundle:Adhesion');
         if ($adhesion == 1){
          $adhesion = $repository->adherer($email,$annee);
         }     
             
         return  $this->gestionCompteAction( $request, $email);
        
        
        }

        
             public function changePasswordAction(Request $request, $email)
            {
                 $password = $_POST['_password'];
                 $conf_password = $_POST['_conf_password'];
                 
                 if($password == $conf_password){
                 
                        $user = new User;
                    $salt = substr(md5(time()),0,10);
      
                
                    $factory = $this->get('security.encoder_factory');
                    $encoder = $factory->getEncoder($user);
                    $newpassword = $encoder->encodePassword($password, $salt);
      

 
        
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCUserBundle:User');
         
          $repository->changePassword($email,$newpassword, $salt);
            $request->getSession()->getFlashBag()->add('info', 'mot de passe modifié ');
$message = \Swift_Message::newInstance()
        
                        
        ->setSubject('Changement de mot de passe ')
        ->setFrom($request->getSession()->get('email'))
        ->setTo($email)
        ->setBody('Votre mot de passe a changé voici le nouveau que vous devez aller modifier :'.$password)
                        
    ;

    $this->get('mailer')->send($message);
            
                 }else{
                     $request->getSession()->getFlashBag()->add('info', 'Mot de passe de confirmation incorrecte ');
                 } 
            
         return  $this->gestionCompteAction( $request, $email);
        
        
    }
 
    public function viewAllStagesUserAction($email, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $season = new Saison;
        $year = $season->connaitreSaison();
        $saison = $em->getRepository('SC\ActiviteBundle\Entity\Saison')->find($year);
        
        if (is_null($email)) {
            $response = new Response;
            $response->setContent("Error 404: not found");
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            return $response;
        } else {
            $user = new User();
            $user = $em->getRepository('SC\UserBundle\Entity\User')->findOneByEmail($email);
        
            $listeInscriptionStages = $em->getRepository('SC\ActiviteBundle\Entity\InscriptionStage')
                ->inscriptionStageActivite($user->getEmail());
            return $this->render('SCActiviteBundle:Stage:viewAllStagesUser.html.twig',
                    array('listeInscriptionStages' => $listeInscriptionStages, 'email'=>$email));
        }
    }
    
    public function tresorerieAction(Request $request)
    {   $em = $this->getDoctrine()->getManager();
         
        $listUser = $em->getRepository('SCUserBundle:User')->findAll();//tresorerie concernant tous les users mêmes les désactivé 
        
        $saison =new Saison;
        $annee = $saison->connaitreSaison();
        
        $payés = $em->getRepository('SCUserBundle:Adhesion')->findby(array('saison'=> $annee));
        
        $sommeDetteActivité = 0;
        $sommeMontantActivité = 0;
        $sommeDetteStage = 0;
        $sommeMontantStage = 0;
        
        foreach( $listUser as $user){
            $email = $user->getUsername();
            
            $user = $em->getRepository('SCUserBundle:Adhesion')->findOneby(array('saison'=> $annee,'user'=>$email));
            $listActivitéPayé[$email] = $user->getMontantPaye();
            $sommeMontantActivité = $sommeMontantActivité + $user->getMontantPaye();
            
            $dette = $em->getRepository('SCActiviteBundle:InscriptionActivite')->getSommeApayer($email);
            $listDetteActivité[$email]= $dette;
            $sommeDetteActivité =$sommeDetteActivité +$dette;
            
            
            $detteStage = $em->getRepository('SCActiviteBundle:InscriptionStage')->totalStage($email);
            $listDetteStage[$email] = $detteStage;
            $sommeDetteStage =$sommeDetteStage +$detteStage;
                    
            $stagePayé = $em->getRepository('SCActiviteBundle:InscriptionStage')->totalStagePayé($email);
            $listStagePayé[$email] = $stagePayé ; 
            $sommeMontantStage = $sommeMontantStage + $stagePayé;
             
            
        }
        

        return $this->render('SCUserBundle:Admin:tresorerie.html.twig',
                array('listUser' => $listUser,
                     'listDetteActivité'=>$listDetteActivité,
                    'listActivitéPayé'=>$listActivitéPayé,
                    'listDetteStage'=>$listDetteStage,
                    'listStagePayé'=>$listStagePayé,
                    'sommeMontantActivité'=> $sommeMontantActivité,
                    'sommeDetteActivité'=>$sommeDetteActivité,
                    'sommeMontantStage'=>$sommeMontantStage,
                    'sommeDetteStage'=>$sommeDetteStage
                )
                
                );
    } 
    
    
    public function affecterGroupeAction($id,$email,$prenomEnfant,$nomEnfant,Request $request)
    {
        $niveauSki = null;
        $em = $this ->getDoctrine() ->getManager();
        $activite = $em->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);
        if (null === $activite) {
          throw new NotFoundHttpException("L'activité d'id ".$id." n'existe pas.");
        }
        $saison = new Saison ();
        $year = $saison->connaitreSaison();  
        $saison = $em->getRepository('SC\ActiviteBundle\Entity\Saison')->find($year);
        if (null === $saison) {
          throw $this -> createNotFoundException("La saison ".$annee." n'existe pas.");
        }
        $repository = $em ->getRepository('SCActiviteBundle:InscriptionActivite');
        $groupe = $_POST['_groupe'];
        $enfantInscrit = $repository -> findOneBy(array('email' => $email,'prenomEnfant' => $prenomEnfant,'nomEnfant' => $nomEnfant,'activite' => $activite, 'saison' =>$saison )) ; 
        if (null === $enfantInscrit) {
          throw $this -> createNotFoundException("L'enfant ".$prenomEnfant.$nomEnfant."n/'est pas inscrit à l'activité ");
        }
        $enfantInscrit -> setGroupe($groupe);
        $em -> flush();
        $listeEnfantsInscrits = $repository -> listeDeMesInscriptions($email);
        
        $repository = $em -> getRepository('SCUserBundle:User');
        $user = $repository ->find($email);
        $repository = $em -> getRepository('SCUserBundle:Enfant');
        // pour chaque enfant inscrit à l'activité, on récupère son niveau de ski
        foreach ( $listeEnfantsInscrits as $inscrit )
        {
            $enfant = $repository ->findOneBy(array('userParent' => $user,'prenomEnfant' => $inscrit -> getPrenomEnfant(),'nomEnfant' => $inscrit -> getNomEnfant()));
            $niveauSki[$email.$inscrit->getPrenomEnfant().$inscrit->getNomEnfant()] = $enfant -> getNiveauSki() -> getNiveau();
            
        }
        return $this->render('SCUserBundle:Admin:gestionEnfant.html.twig',array('listeEnfantsInscrits' => $listeEnfantsInscrits,'niveauSki' => $niveauSki));
        
    }

    public function contactAction(Request $request)
    {       
        return $this->render('SCUserBundle:Admin:contact.html.twig');
    }
   
}
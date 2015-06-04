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
        $request->getSession()->getFlashBag()->add('info', 'Compte bien activité ');
        
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
    

    public function gestionEnfantAction($email)
    {
        $valide = false;
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCActiviteBundle:InscriptionActivite');

        $listeEnfantsInscrits = $repository -> listeDeMesInscriptions($email);   
        return $this->render('SCUserBundle:Admin:gestionEnfant.html.twig',array('listeEnfantsInscrits' => $listeEnfantsInscrits, 'valide' => $valide));
        
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
         
        $listUser = $em->getRepository('SCUserBundle:User')->findAll();
        $saison =new Saison;
        $annee = $saison->connaitreSaison();
        $payés = $em->getRepository('SCUserBundle:Adhesion')->findby(array('saison'=> $annee));
        $somme = 0;
        foreach( $listUser as $user){
          $email = $user->getUsername();
            $dette = $em->getRepository('SCActiviteBundle:InscriptionActivite')->getSommeApayer($email);
            
            $dettes[$email]= $dette;
            $sommeDette = $somme + $dette ; 
            
        }
        

        return $this->render('SCUserBundle:Admin:tresorerie.html.twig',
                array('listUser' => $listUser,
                     'listDette'=>$dettes,
                    'listAdhesion'=>$payés,
                    'sommeDette'=> $sommeDette
                )
                
                );
    } 
    
}
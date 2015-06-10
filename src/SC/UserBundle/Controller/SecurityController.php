<?php
// src/SC/UserBundle/Controller/SecurityController.php;

namespace SC\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use SC\UserBundle\Entity\User;
use SC\UserBundle\Form\UserType;

use SC\UserBundle\Entity\Adhesion;
use \SC\ActiviteBundle\Entity\Saison;

class SecurityController extends Controller
{
  public function loginAction(Request $request)
  { 
    $session = $request->getSession();
    // Si le visiteur est déjà identifié, on le redirige vers l'accueil
    if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
      
      return $this->redirect($this->generateUrl('sc_user_homepage'));
    }

   
    // On vérifie s'il y a des erreurs d'une précédente soumission du formulaire
    if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
      $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
      
    } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
    }
      
    return $this->render('SCUserBundle:Security:login.html.twig', array(
      // Valeur du précédent nom d'utilisateur entré par l'internaute
      'last_username' => $session->get(SecurityContext::LAST_USERNAME),
      'error'         => $error,
    ));
    
  }
  
  // permet de recuperer les paramètres d'un compte (parametre de la table adhesion)
  public function paramCompteAction(Request $request)
  { 
    $session = $request->getSession();
      
    $usr = $this->get('security.context')->getToken()->getUser();
    $email = $session->get('email');
       
    $saison = new Saison;
    $annee = $saison->connaitreSaison();
        
    $repository = $this
        ->getDoctrine()
        ->getManager()
        ->getRepository('SCUserBundle:Adhesion');
        
    $adhesion = $repository->findOneby(array('user' => $email,'saison'=> $annee ));
    
    //On cherche le montant que l'utilisateur doit au total pour une saison 
    $em = $this->getDoctrine()->getManager();

    $dette = $em->getRepository('SCActiviteBundle:InscriptionActivite')->getSommeApayer($email);
             
    return $this->render('SCUserBundle:Security:paramCompte.html.twig',array(
            'user' => $usr ,
            'adhesion'=>$adhesion,
            'dette'=>$dette
        
                
            )
        );
    
    }
    
    //methode appelé lors de la connection
    public function compteAction(Request $request)
    {      
        $session = $request->getSession();
        //recupere l'email de la personne connecté 
        $usr= $this->get('security.context')->getToken()->getUser();
        $email = $usr->getUsername();
        //son type
        $type = $usr->getType();
        // s'il est primaire
        $isPrimaire = $usr->getIsprimaire();
        
        // si l'utilisateur n'est pas primaire on stock l'email primaire dans session
        if (!$isPrimaire)
        {
            $emailSecondaire = $email;
            $repository = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('SCUserBundle:User')
            ;  
        $user = $repository->findOneby(['email' => $email]);
        
        $email = $user->getEmailPrimaire();
        // et on stock l'email secondaire
        $session->set('emailSecondaire',$emailSecondaire );        
        }else{
            $repository = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('SCUserBundle:User')
            ;
            
        $user = $repository->findOneby(['email' => $email]);
        }
        
        
        $session->set('email',$email );
        $session->set('isPrimaire',$isPrimaire );
        
        $saison = new Saison;
        $annee = $saison->connaitreSaison();
           
        $em = $this->getDoctrine()->getManager();
        
        // ajout de la saiosn s'il elle 'existe pas 
        $newSaison = new Saison;
        $year = $newSaison->connaitreSaison();
        $request->getSession()->set('year', $year);
        $newSaison = $em -> getRepository('SCActiviteBundle:Saison') -> find($year);
        
        if (null === $newSaison) {
            $newSaison = new Saison();
            $newSaison->setAnnee($year);
            $em->persist($newSaison);
            $em->flush();
        }
        
        //on le rentre dans la table adhesion si il est pas encore inscrit        
        $adhesion = $em->getRepository('SCUserBundle:Adhesion')->findOneby(
                   array('user' => $email,
                         'saison'=> $annee
                   ));
        
        if ($adhesion == null)
        {
            $season = new Saison;
            $year = $season->connaitreSaison();
            
            $request->getSession()->set('year', $year);
            $saison = $em -> getRepository('SCActiviteBundle:Saison') -> find($year);
        
            if (null === $saison) {
                $saison = new Saison();
                $saison->setAnnee($year);
                $em->persist($saison);
                $em->flush();
            }    
            
            $saison_actuel = $em->getRepository('SC\ActiviteBundle\Entity\Saison')->find($annee);
            $adhesion = new Adhesion;
            $adhesion->setAdhesionAnnuel(false);
            //$adhesion->setModalite(0);
            $adhesion->setMontantPaye(0);
            $adhesion->setRemise(0);
            $adhesion->setSaison($saison_actuel);
            $adhesion->setUser($user);
            $em->persist($adhesion);
            $em->flush();
        }           
        
        return $this->render('SCUserBundle:Security:monCompte.html.twig',array(
                    'nom' => $email
                    )
                );
        
    }
    
    //permet d'afficher les enfants d'un user
    public function compteEnfantAction(Request $request)
    {    
        $session = $request->getSession();           
        $email = $session->get('email');// recupère l'email de la session
       
        //recupere de la liste
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('SCUserBundle:Enfant')
        ;
        $listEnfants = $repository->findby(['userParent' => $email]);
        
        return $this->render('SCUserBundle:Security:monCompteEnfant.html.twig',array(
                    'listEnfants'=> $listEnfants 
                    )
                );
        
    }
    
    //permet à un user de s'inscrire
    public function registerAction(Request $request)
    {
        // Si le visiteur est déjà identifié, on le redirige vers son compte
         if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('sc_user_compte'));
        }
       
      
        $user = new User;
        //création du salt aléatoire
        $salt = substr(md5(time()),0,10);
        $user->setSalt($salt );
        $user->setType('client');
        $user->setIsActive(false);
        $user->setIsPrimaire(true);
        //Creation du formulaire
        $form = $this->get('form.factory')->create(new UserType(), $user);       
      
        // il faut aussi remplir la table adhesion de l'annee actuel 
      
        $adhesion = new Adhesion;
        $adhesion->setAdhesionAnnuel(false);
        //$adhesion->setModalite(1);
        $adhesion->setMontantPaye(0);
        $adhesion->setRemise(0);
      
        $saison_actuel = new Saison;
        $year = $saison_actuel->connaitreSaison();
        $saison = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Saison')->findOneByAnnee($year);
        $em = $this -> getDoctrine() ->getManager();
        if (null === $saison) {
            $saison = new Saison();
            $saison->setAnnee($year);
            $em->persist($saison);
            $em->flush();
        }
        $saison = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Saison')->findOneByAnnee($year);
        $adhesion->setSaison($saison);
        
      
        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $user contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);
        //encodage du mot de passe 
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);
        $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
        $user->setPassword($password);
        $adhesion->setUser($user);
        // On vérifie que les valeurs entrées sont correctes
        if ($form->isValid()) {
            // on verifie du'il na pas déja été enregistré
            $em = $this->getDoctrine()->getManager();
            $listUtilisateur = $em->getRepository('SCUserBundle:User')->findAll();
            foreach ($listUtilisateur   as $utilisateur)
            {        
                if ($utilisateur->getEmail() == $user->getEmail())
                {
                    $request->getSession()->getFlashBag()->add('info', 'login déjà existant');
                    return $this->render('SCUserBundle:Security:register.html.twig', array('form' => $form->createView()));    
                }
            }
        // On l'enregistre notre objet $user dans la base de données, par exemple
        
        $em->persist($user);
        $em->flush();
        $ema = $this->getDoctrine()->getManager();
        // On l'enregistre notre objet $adhesion dans la base de données, par exemple
        $ema->persist($adhesion);
        $ema->flush();
        $request->getSession()->getFlashBag()->add('info', 'Utilisateur bien enregistré, vous recevrez un mail après activation de votre compte');
        
        return $this->redirect($this->generateUrl('sc_activite_homepage'));
        }
        
        return $this->render('SCUserBundle:Security:register.html.twig', array(
        'form' => $form->createView(),
        ));
  }
  //permet d'ajouter un utilisateur secondaire
    public function ajouterAction(Request $request)
    {
      
        // Si le visiteur n'est pas primaire 
        $session = $request->getSession();
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            if ($session->get('isPrimaire')== false){
                return $this->redirect($this->generateUrl('sc_user_compte'));
             }
             
        }      
        
        $user = new User;
        //création du salt aléatoire
        $salt = substr(md5(time()),0,10);
        $user->setSalt($salt);
        $user->setType('client');
        $user->setIsActive(true);     
        $user->setIsPrimaire(false);
        $user->setEmailPrimaire($session->get('email'));
      
      
        $form = $this->get('form.factory')->create(new UserType(), $user);
      
        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $user contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);
        
        //encodage du mot de passe 
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);
        $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
        $user->setPassword($password);
        
        
        // On vérifie que les valeurs entrées sont correctes
        if ($form->isValid()) {
            //on verifie que l'utilisateur n'existe pas déja
            $em = $this->getDoctrine()->getManager();
            $listUtilisateur = $em->getRepository('SCUserBundle:User')->findAll();
            foreach ($listUtilisateur   as $utilisateur)
            {          
                if ($utilisateur->getEmail() == $user->getEmail())
                {
                    $request->getSession()->getFlashBag()->add('info', 'login déjà existant');
                    return $this->render('SCUserBundle:Security:register.html.twig', array('form' => $form->createView()));
                       
                }
            }
            $em->persist($user);
            $em->flush();
            $request->getSession()->getFlashBag()->add('info', 'Utilisateur secondaire bien enregistré');
        
            return $this->redirect($this->generateUrl('sc_user_compte'));
        }
        
        return $this->render('SCUserBundle:Security:ajouterUser.html.twig', array(
        'form' => $form->createView(),
        ));
    }
  
    //permet à un user de choisir ses modalité de paiement
    public function choixModaliteAction(Request $request)
    {
        $session = $request->getSession();        
        $email = $session->get('email');     
                 
        $saison = new Saison;
        $annee = $saison->connaitreSaison();
        
        $modalite= $_POST['_modalite'];
        
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCUserBundle:Adhesion');
         
        $adhesion = $repository->choixModalite($email,$annee,$modalite);
        return $this->paramCompteAction($request);
    }
    // permet à un user de changer son password
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

            
            }else{
                   $request->getSession()->getFlashBag()->add('info', 'Mot de passe de confirmation incorrecte ');
            } 
            
        return  $this->paramCompteAction( $request);        
        
    }
  
}

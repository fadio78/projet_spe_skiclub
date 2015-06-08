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
    public function paramCompteAction(Request $request)
  { 
        $usr= $this->get('security.context')->getToken()->getUser();
        $email = $usr->getUsername();
       
        $saison = new Saison;
        $annee = $saison->connaitreSaison();
        
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCUserBundle:Adhesion');
        
        $adhesion = $repository->findOneby(
                   array('user' => $email,
                         'saison'=> $annee
                   ));
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
  public function compteAction(Request $request)
  {
      
      
        $session = $request->getSession();
        $usr= $this->get('security.context')->getToken()->getUser();
        $email = $usr->getUsername();
        $type = $usr->getType();
        $isPrimaire = $usr->getIsprimaire();
        
        // si l'utilisateur n'est pas primaire on stock l'email primaire dans session
        if (!$isPrimaire){
            $emailSecondaire = $email;
            $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('SCUserBundle:User')
             ;
            
            
        $user = $repository->findOneby(['email' => $email]);
        
        $email = $user->getEmailPrimaire();
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
     
        
        //on le rentre dans la table adhesion si il est pas encore inscrit 
        
        
        $adhesion = $em->getRepository('SCUserBundle:Adhesion')->findOneby(
                   array('user' => $email,
                         'saison'=> $annee
                   ));
        
        
        if ($adhesion == null){
            
            
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
            $adhesion->setModalite(0);
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
    public function compteEnfantAction(Request $request)
  {
      
      
        $session = $request->getSession();
      
        
        
        $email = $session->get('email');
       
        
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
  public function registerAction(Request $request){
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
      $form = $this->get('form.factory')->create(new UserType(), $user);

      

    
      
      // il faut aussi remplir la table adhesion de l'annee actuel 
      
      $adhesion = new Adhesion;
      $adhesion->setAdhesionAnnuel(false);
      $adhesion->setModalite(1);
      $adhesion->setMontantPaye(0);
      $adhesion->setRemise(0);
      
      $saison_actuel = new Saison;
      $year = $saison_actuel->connaitreSaison();
      $saison = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Saison')->findOneByAnnee($year);
      $adhesion->setSaison($saison);
      $adhesion->setUser($user);
      
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
         // on verifie si l'enfant n'a pas d'jà été enregistré
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
        // On l'enregistre notre objet $activite dans la base de données, par exemple
        
        $em->persist($user);
        $em->flush();
        $ema = $this->getDoctrine()->getManager();
        $ema->persist($adhesion);
        $ema->flush();
        $request->getSession()->getFlashBag()->add('info', 'Utilisateur bien enregistré, vous recevrez un mail après activation de votre compte');
        // On redirige vers la page de visualisation de l'annonce nouvellement créée
        return $this->redirect($this->generateUrl('sc_activite_homepage'));
        }
        
        return $this->render('SCUserBundle:Security:register.html.twig', array(
        'form' => $form->createView(),
        ));
  }
  //permet d'ajouter un utilisateur secondaire
    public function ajouterAction(Request $request){
      
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
      $user->setType('user');
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
        // On redirige vers la page de visualisation de l'annonce nouvellement créée
        return $this->redirect($this->generateUrl('sc_user_compte'));
        }
        
        return $this->render('SCUserBundle:Security:ajouterUser.html.twig', array(
        'form' => $form->createView(),
        ));
  }
  
}

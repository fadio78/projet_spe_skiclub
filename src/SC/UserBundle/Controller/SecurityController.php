<?php
// src/SC/UserBundle/Controller/SecurityController.php;

namespace SC\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use SC\UserBundle\Entity\User;
use SC\UserBundle\Form\UserType;

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
  public function compteAction(Request $request)
  {
        $session = $request->getSession();
        $usr= $this->get('security.context')->getToken()->getUser();
        $email = $usr->getUsername();
        $type = $usr->getType();
        $isPrimaire = $usr->getIsprimaire();
        $session->set('email',$email );
        $session->set('type',$type );
        $session->set('isPrimaire',$isPrimaire );
        
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('SCUserBundle:Enfant')
             ;
        $listEnfants = $repository->findby(['userParent' => $email]);
        
        return $this->render('SCUserBundle:Security:monCompte.html.twig',array(
        'nom' => $email , 
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
      $user->setSalt('voir plus tard' );
      $user->setType('user');
      $user->setIsActive(true);
      
      $user->setIsPrimaire(true);
      
     
      $form = $this->get('form.factory')->create(new UserType(), $user);
      
      // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $user contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);
        // On vérifie que les valeurs entrées sont correctes
        if ($form->isValid()) {
        // On l'enregistre notre objet $activite dans la base de données, par exemple
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        $request->getSession()->getFlashBag()->add('info', 'User bien enregistré');
        // On redirige vers la page de visualisation de l'annonce nouvellement créée
        return $this->redirect($this->generateUrl('sc_user_homepage'));
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
      $user->setSalt('voir plus tard' );
      $user->setType('user');
      $user->setIsActive(true);     
      $user->setIsPrimaire(false);
      $user->setEmailPrimaire($session->get('email'));
      
      $form = $this->get('form.factory')->create(new UserType(), $user);
      
      // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $user contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);
        // On vérifie que les valeurs entrées sont correctes
        if ($form->isValid()) {
        // On l'enregistre notre objet $activite dans la base de données, par exemple
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        $request->getSession()->getFlashBag()->add('info', 'User secondaire bien enregistré');
        // On redirige vers la page de visualisation de l'annonce nouvellement créée
        return $this->redirect($this->generateUrl('sc_user_compte'));
        }
        
        return $this->render('SCUserBundle:Security:ajouterUser.html.twig', array(
        'form' => $form->createView(),
        ));
  }
  
}

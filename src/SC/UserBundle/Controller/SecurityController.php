<?php
// src/SC/UserBundle/Controller/SecurityController.php;

namespace SC\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

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
        $session->set('email',$email );
        $session->set('type',$type );
        return $this->render('SCUserBundle:Security:monCompte.html.twig',array(
        'nom' => $email
        )
        );
  }
  
}

<?php
// src/SC/UserBundle/Controller/EnfantController.php;
namespace SC\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use SC\UserBundle\Entity\User;
use SC\UserBundle\Entity\Enfant;
use SC\UserBundle\Form\EnfantType;

class EnfantController extends Controller
{
    public function ajouterAction(Request $request){
    
      $enfant = new Enfant;
      //$session = $request->getSession();
      $usr= $this->get('security.context')->getToken()->getUser();
      $enfant->setUserParent($usr);
      $form = $this->get('form.factory')->create(new EnfantType(), $enfant);
      
        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $user contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);
        // On vérifie que les valeurs entrées sont correctes
        if ($form->isValid()) {
        // On l'enregistre notre objet $activite dans la base de données, par exemple
        $em = $this->getDoctrine()->getManager();
        $em->persist($enfant);
        $em->flush();
        $request->getSession()->getFlashBag()->add('info', 'enfant bien enregistré');
        // On redirige vers la page de visualisation de l'annonce nouvellement créée
        return $this->redirect($this->generateUrl('sc_user_compte'));
        }
        
        return $this->render('SCUserBundle:Enfant:formEnfant.html.twig', array(
        'form' => $form->createView(),
        ));
    }
}

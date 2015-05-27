<?php
// src/SC/ActiviteBundle/Controller/ActiviteController.php
namespace SC\ActiviteBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use SC\ActiviteBundle\Entity\Activite;
use SC\ActiviteBundle\Form\ActiviteType;
use SC\UserBundle\Entity\User;
use SC\UserBundle\Entity\Licence;


class ActiviteController extends Controller 
{
    
    public function indexAction()
    {
    // On ne sait pas combien de pages il y a
    // Mais on sait qu'une page doit être supérieure ou égale à 1
       /*if ($page < 1) {
      // On déclenche une exception NotFoundHttpException, cela va afficher
      // une page d'erreur 404 (qu'on pourra personnaliser plus tard d'ailleurs)
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        } */
      // Ici, on récupérera la liste des activités, puis on la passera au template
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCActiviteBundle:Activite');
        $listeActivites = $repository->findAll();  
        
        return $this->render('SCActiviteBundle::index.html.twig',array('listeActivites' => $listeActivites ));
    }
    
    
    
    
    
    public function viewAction($id)
    {
        // Ici, on récupérera l'activité correspondante à l'id $id
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCActiviteBundle:Activite');
        $activite = $repository->find($id); 
        if (null === $activite) {
          throw new NotFoundHttpException("L'activité d'id ".$id." n'existe pas.");
        }

        return $this->render('SCActiviteBundle::view.html.twig', array('activite' => $activite));
    }
    
    
    
    
    
    public function addAction(Request $request)
    {
        // On crée l'utilisateur
        $user = new User;
        $Repository = $this->getDoctrine()->getManager()->getRepository('SC\UserBundle\Entity\User')->findAll();
        $user=$Repository[0];
   
        // On crée un objet Advert
        $activite = new Activite();
        $activite->setUser($user);
        
        $form = $this->get('form.factory')->create(new ActiviteType(), $activite);
        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $activite contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);
        // On vérifie que les valeurs entrées sont correctes
        if ($form->isValid()) {
        // On l'enregistre notre objet $activite dans la base de données, par exemple
        $em = $this->getDoctrine()->getManager();
        $em->persist($activite);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'Activité bien enregistrée.');
        // On redirige vers la page de visualisation de l'annonce nouvellement créée
        return $this->redirect($this->generateUrl('sc_activite_view', array('id' => $activite->getId())));
        }
        // À ce stade, le formulaire n'est pas valide car :
        // - Soit la requête est de type GET, donc l'admin vient d'arriver sur la page et veut voir le formulaire
        // - Soit la requête est de type POST, mais le formulaire contient des valeurs invalides, donc on l'affiche de nouveau
        return $this->render('SCActiviteBundle::add.html.twig', array(
        'form' => $form->createView(),
        ));
    
    } 
  
  
  
  
   /*
  
    public function editAction($id, Request $request)
    {
    // Ici, on récupérera l'activité correspondante à $id
    // Même mécanisme que pour l'ajout
        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');
            return $this->redirect($this->generateUrl('sc_activite_view', array('id' => 5)));
        }
        return $this->render('SCActiviteBundle:Activite:edit.html.twig');
    }
  
  
  
  
    public function deleteAction($id)
    {
    // Ici, on récupérera l'activité correspondant à $id
    // Ici, on gérera la suppression de l'activité en question
        return $this->render('SCActiviteBundle:Activite:delete.html.twig');
    }
    */
  
  
} 
  





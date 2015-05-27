<?php
// src/SC/ActiviteBundle/Controller/ActiviteController.php
namespace SC\ActiviteBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use SC\ActiviteBundle\Entity\Activite;


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
        }
      // Ici, on récupérera la liste des activités, puis on la passera au template
        */  
        
        return $this->render('SCActiviteBundle::index.html.twig');
    }
    
    
    
    
    /*
    public function viewAction($id)
    {
    // Ici, on récupérera l'activité correspondante à l'id $id
        return $this->render('SCActiviteBundle:Activite:view.html.twig', array(
      'id' => $id
        ));
    }
    
    */
    
    
    
    public function addAction(Request $request)
    {
        
        // On crée un objet Advert
        $activite = new Activite();
        // On crée le FormBuilder grâce au service form factory
        $form = $this->get('form.factory')->createBuilder('form', $activite)
          ->add('nomactivite','text')
          ->add('description','textarea')
          ->add('prixactivite','number')
          ->add('enregistrer','submit')
          ->getForm();
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
  





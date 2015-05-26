<?php
// src/SC/ActiviteBundle/Controller/ActiviteController.php
namespace SC\ActiviteBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use OC\PlatformBundle\Entity\Activite;


class ActiviteController extends Controller 
{
    /*
    public function indexAction($page)
    {
    // On ne sait pas combien de pages il y a
    // Mais on sait qu'une page doit être supérieure ou égale à 1
        if ($page < 1) {
      // On déclenche une exception NotFoundHttpException, cela va afficher
      // une page d'erreur 404 (qu'on pourra personnaliser plus tard d'ailleurs)
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }
      // Ici, on récupérera la liste des activités, puis on la passera au template
          
        
        return $this->render('SCActiviteBundle:Activite:index.html.twig');
    }
    */
    
    
    
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
        $formBuilder = $this->get('form.factory')->createBuilder('form', $activite);
        // On ajoute les champs de l'entité que l'on veut à notre formulaire
      $formBuilder
        ->add('Nom activité','text')
        ->add('Description','textarea')
        ->add('Prix activité','number')
        ->add('Prix licence ','number')
        ->add('enregistrer','submit');

        // À partir du formBuilder, on génère le formulaire
        $form = $formBuilder->getForm();
        // On passe la méthode createView() du formulaire à la vue
        // afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('SCActiviteBundle:Activite:add.html.twig', array(
          'form' => $form->createView(),
        ));
    } 
  
  
  
  
   /*
  
    // Si la requête est en POST, c'est que l'admin a soumis le formulaire
        if ($request->isMethod('POST')) {
      // Ici, on s'occupera de la création et de la gestion du formulaire
            $request->getSession()->getFlashBag()->add('notice', 'Activité bien enregistrée.');
      // Puis on redirige vers la page de visualisation de cettte activité
            return $this->redirect($this->generateUrl('sc_activite_view', array('id' => 5)));
        }
    // Si on n'est pas en POST, alors on affiche le formulaire
        return $this->render('SCActiviteBundle:Activite:add.html.twig');
     
     */
 
  
  
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
  





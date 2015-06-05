<?php
// src/SC/LicenceBundle/Controller/LicenceController.php
namespace SC\LicenceBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use SC\LicenceBundle\Entity\Licence;
use SC\LicenceBundle\Form\LicenceType;
use SC\LicenceBundle\Form\LicenceEditType;




class LicenceController extends Controller 
{
    
    public function indexAction(Request $request)
    {
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCLicenceBundle:Licence');
        $listeLicences = $repository->findAll(); 
        $session = $request->getSession();
        $type = $session->get('type');
        
        return $this->render('SCLicenceBundle::indexlicence.html.twig',array('listeLicences' => $listeLicences ,'type' => $type ));
        
    }
    
    
    
        
    public function viewAction($typeLicence,Request $request)
    {
        $session = $request->getSession();
        $type = $session->get('type');
        // Ici, on récupérera la licence correspondant à typeLicence
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCLicenceBundle:Licence');
        //on recupere la licence
        $licence = $repository->find($typeLicence);
        if (null === $licence) {
          throw new NotFoundHttpException("La licence de type ".$typeLicence." n'existe pas.");
        }
        
        return $this->render('SCLicenceBundle::viewlicence.html.twig', array('licence' => $licence,'type' => $type));
    }
    
    
    
    
    public function addAction(Request $request)
    {

        $session = $request->getSession();
        $type = $session->get('type');
        // On crée un objet Licence
        $licence = new Licence();
        $form = $this->get('form.factory')->create(new LicenceType(), $licence);
        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $licence contient les valeurs entrées dans le formulaire par l'e visiteur l'admin
        $form->handleRequest($request);
        // On vérifie que les valeurs entrées sont correctes
        if ($form->isValid()) {
        // On l'enregistre notre objet $licence dans la base de données, par exemple
        $em = $this->getDoctrine()->getManager();
        $em->persist($licence);
        $em->flush();
        $request->getSession()->getFlashBag()->add('info', 'Licence bien enregistrée');
        // On redirige vers la page de visualisation de la licence nouvellement créée
        return $this->redirect($this->generateUrl('sc_licence_view', array('typeLicence' => $licence->getTypeLicence(),'type' => $type)));
        }
        // À ce stade, le formulaire n'est pas valide car :
        // - Soit la requête est de type GET, donc l'admin vient d'arriver sur la page et veut voir le formulaire
        // - Soit la requête est de type POST, mais le formulaire contient des valeurs invalides, donc on l'affiche de nouveau
        return $this->render('SCLicenceBundle::addlicence.html.twig', array(
        'form' => $form->createView(),'type' => $type
        ));
        
    }
    
    public function editAction($typeLicence , Request $request)
    {
        
        $session = $request->getSession();
        $type = $session->get('type');
        $em = $this->getDoctrine()->getManager();

        // On récupère la licence de type typeLicence
        $licence = $em->getRepository('SCLicenceBundle:Licence')-> find ($typeLicence);

        if (null === $licence) {
            throw new NotFoundHttpException("La licence de type  ".$typeLicence." n'existe pas.");
        }

        $form = $this->createForm(new LicenceEditType(), $licence);

        if ($form->handleRequest($request)->isValid()) {
        // Inutile de persister ici, Doctrine connait déjà notre licence
        $em->flush();

        $request->getSession()->getFlashBag()->add('info', 'Licence bien modifiée.');

          return $this->redirect($this->generateUrl('sc_licence_view', array('typeLicence' => $licence->getTypeLicence())));
        }

        return $this->render('SCLicenceBundle::editlicence.html.twig', array('form'   => $form->createView(),'licence' => $licence, 'type' =>$type ));
    }
    
  
  
  /*
    public function deleteAction($typeLicence ,Request $request )
    {
        $session = $request->getSession();
        $type = $session->get('type');
        //on recupere l'entity manager
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('SCLicenceBundle:Licence');
        //on recupere la licence
        $licence = $repository -> find( $typeLicence);
        
        //si n'existe pas -> message d'erreur
        if (is_null($licence)) {
            // erreur a lance
            //return new Response('probleme cette licence nexiste pas');
        }
        else {
            $em->remove($licence);
            $em->flush();
            $listeLicences = $em->getRepository('SCLicenceBundle:Licence')->findAll();
            return $this->render('SCLicenceBundle::indexlicence.html.twig', array('listeLicences' => $listeLicences,'type' => $type));
        }    
    }  */
    
            
            
            
            
            
            
            
           
}
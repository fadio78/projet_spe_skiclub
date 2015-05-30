<?php
// src/SC/ActiviteBundle/Controller/ActiviteController.php
namespace SC\ActiviteBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use SC\ActiviteBundle\Entity\Activite;
use SC\ActiviteBundle\Form\ActiviteType;

use SC\ActiviteBundle\Form\LieuType;
use SC\ActiviteBundle\Form\SortieType;

use SC\ActiviteBundle\Form\ActiviteEditType;

use SC\UserBundle\Entity\User;
use SC\UserBundle\Entity\Licence;
use SC\ActiviteBundle\Entity\Sortie;
use SC\ActiviteBundle\Entity\Lieu;
use SC\ActiviteBundle\Entity\Saison;
use SC\ActiviteBundle\Entity\SaisonRepository;
use SC\ActiviteBundle\Entity\InscriptionActivite;
use SC\UserBundle\Entity\Enfant;



class ActiviteController extends Controller 
{
    
    public function indexAction(Request $request)
    {
        $em = $this -> getDoctrine() ->getManager();
        $year = $this->connaitreSaison();
        $saison = $em -> getRepository('SCActiviteBundle:Saison') -> find($year);
        
        if (null === $saison) {
            $saison = new Saison();
            $saison->setAnnee($year);
            $em->persist($saison);
            $em->flush();
        }

     
     
    // On ne sait pas combien de pages il y a
    // Mais on sait qu'une page doit être supérieure ou égale à 1
       /*if ($page < 1) {
      // On déclenche une exception NotFoundHttpException, cela va afficher
      // une page d'erreur 404 (qu'on pourra personnaliser plus tard d'ailleurs)
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        } */
      // Ici, on récupérera la liste des activités, puis on la passera au template
        $repository = $em ->getRepository('SCActiviteBundle:Activite');
        $listeActivites = $repository->findAll();  
           return $this->render('SCActiviteBundle:Activite:index.html.twig',array('listeActivites' => $listeActivites,'year' =>$year
        ));
    }
    
    
    
    
    
    public function viewAction($id,Request $request)
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
        return $this->render('SCActiviteBundle:Activite:view.html.twig', array('activite' => $activite));
    }
    
    
    
    
    
    public function addAction(Request $request)
    {
        // On récupère à partir de la session l'utilisateur
        $session = $request->getSession();
        $email = $session->get('email');
        $year = $this->connaitreSaison();
        $user = $this->getDoctrine()->getManager()->getRepository('SC\UserBundle\Entity\User')->find($email);
        // On crée un objet Activite
        $activite = new Activite();
        $activite->setUser($user);
        $form = $this->get('form.factory')->create(new ActiviteType(), $activite);
        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $activite contient les valeurs entrées dans le formulaire par l'e visiteur l'admin
        $form->handleRequest($request);
        // On vérifie que les valeurs entrées sont correctes
        if ($form->isValid()) {
            $saison = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Saison')->find($year);
            $saison -> addActivite($activite);
            // On l'enregistre notre objet $activite dans la base de données, par exemple
            $em = $this->getDoctrine()->getManager();
            $em->persist($activite);
            $em->flush();
            $request->getSession()->getFlashBag()->add('info', 'Activité bien enregistrée');
            // On redirige vers la page de visualisation de l'activité nouvellement créée
            return $this->redirect($this->generateUrl('sc_activite_view', array('id' => $activite->getId())));
        }
        // À ce stade, le formulaire n'est pas valide car :
        // - Soit la requête est de type GET, donc l'admin vient d'arriver sur la page et veut voir le formulaire
        // - Soit la requête est de type POST, mais le formulaire contient des valeurs invalides, donc on l'affiche de nouveau
        return $this->render('SCActiviteBundle:Activite:add.html.twig', array('form' => $form->createView()
        ));
        
    } 
  
 
  
 

    public function editAction($id, Request $request)
    {
     
        $em = $this->getDoctrine()->getManager();

        // On récupère l'activité $id
        $activite = $em->getRepository('SCActiviteBundle:Activite')->find($id);

        if (null === $activite) {
            throw new NotFoundHttpException("L'activité d'id ".$id." n'existe pas.");
        }

        $form = $this->createForm(new ActiviteEditType(), $activite);

        if ($form->handleRequest($request)->isValid()) {
        // Inutile de persister ici, Doctrine connait déjà notre activité
            $em->flush();
            $request->getSession()->getFlashBag()->add('info', 'Activité bien modifiée');
            return $this->redirect($this->generateUrl('sc_activite_view', array('id' => $activite->getId())));
        }

        return $this->render('SCActiviteBundle:Activite:edit.html.twig', array('form'   => $form->createView(),'activite' => $activite));// Je passe également l'activité à la vue si jamais elle veut l'afficher))
    }
    
  
  
  
    public function deleteAction($id,Request $request)
    {
        $year = $this->connaitreSaison();
        //on recupere l'entity managere 
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('SCActiviteBundle:Activite');
        //on recupere l'activite
        $activite = $repository->find($id);
        
        //si n'existe pas -> message d'erreur
        if (is_null($activite)) {
            $response = new Response;
            $response->setContent("Error 404: not found");
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            return $response;          
        }
        else {
            $saison = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Saison')->find($year);
            $saison -> removeActivite($activite);
            $em->remove($activite);
            $em->flush();
            $listeActivites = $em->getRepository('SCActiviteBundle:Activite')->findAll();
            return $this->render('SCActiviteBundle:Activite:index.html.twig', array('listeActivites' => $listeActivites));
        }    
    }

    public function ajoutSortieAction($id,Request $request) {
        
        $sortie = new Sortie();
        $sortie->setActivite($this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id));
            
        // On crée le FormBuilder grâce au service form factory
        $form = $this->get('form.factory')->createBuilder('form', $sortie)
          ->add('dateSortie','text')
          ->add('lieu','text')
          ->add('enregistrer','submit')
          ->getForm();
        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $activite contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);
        // On vérifie que les valeurs entrées sont correctes
        if ($form->isValid()) {
            // On l'enregistre notre objet $activite dans la base de données, par exemple
            $em = $this->getDoctrine()->getManager();
            $em->persist($sortie);
            $em->flush();
            return $this->redirect($this->generateUrl('sc_activite_view', array('id' => $activite->getId())));
        }
            return $this->render('SCActiviteBundle:Activite:add.html.twig', array(
            'form' => $form->createView(),
            ));
    }
    
    // permet de connaitre la saison courante
    public function connaitreSaison() {
        
        $date = new \DateTime();
        $annee = $date->format('Y');
        $mois = $date->format('m');
        
        if ($mois > 8) {
            return $annee;
        }
        else {
            return $annee-1;
        }
    }
    
    /*
    public function inscriptionActiviteAction($id,Request $request) 
    {
        $em = $this->getDoctrine()->getManager();
        $enfant = new Enfant ();
        $session = $request->getSession();
        $email = $session->get('email');
        $inscriptionActivite = new InscriptionActivite();
        $activite = $em->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);
        $inscriptionActivite -> setActivite($activite);
        $inscriptionActivite -> setPrixPayeActivite(0);

        $year = $this->connaitreSaison();  
        $saison = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Saison')->find($year);
        $inscriptionActivite -> setSaison($saison);
    
        $enfant = new Enfant ();
        $form = $this->get('form.factory')->createBuilder('form', $enfant)
          ->add('prenomEnfant', 'entity', array('class'=> 'SCUserBundle:Enfant','property' => 'PrenomEnfant','query_builder' => function(EntityRepository $er) {
        return $er->createQueryBuilder('e')
          ->orderBy('u.username', 'ASC')},
          'multiple' => false,'expanded' => false,'required' => true))
          ->add('enregistrer','submit')
          ->getForm();

        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $activite contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);
        // On vérifie que les valeurs entrées sont correctes
        if ($form->isValid()){
            $prenomEnfant = $enfant ->getPrenomEnfant();
            $nomEnfant =$enfant ->getNomEnfant();
             //$prenomEnfant = $inscriptionActivite -> getPrenomEnfant();
             //$nomEnfant = $inscriptionActivite ->getNomEnfant();
             
             $enfant=$em->getRepository('SC\ActiviteBundle\Entity\Activite')->find(array('email' => $email, 'prenomEnfant' => $prenomEnfant, 'nomEnfant' => $nomEnfant));
             $inscriptionActivite ->setPrenomEnfant($enfant);
             $inscriptionActivite ->setNomEnfant($enfant);
             $inscriptionActivite ->setEmail($enfant);
            // On l'enregistre notre objet $activite dans la base de données, par exemple
            $em->persist($inscriptionActivite);
            $em->flush();
            $request->getSession()->getFlashBag()->add('info', 'Inscription bien enregistrée');
            return $this->redirect($this->generateUrl('sc_activite_homepage'));
        }
            return $this->render('SCActiviteBundle:InscriptionActivite:addinscriptionactivite.html.twig', array(
            'form' => $form->createView(),
            ));
        
        
        
    }
    */
    
}
